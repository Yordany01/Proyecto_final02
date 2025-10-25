<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $DBGroup = 'default';

    public function getKpis(array $filters = []): array
    {
        $db = \Config\Database::connect();
        $kpis = [
            'total_tareas' => 0,
            'pendientes' => 0,
            'completados' => 0,
            'empleados' => 0,
        ];

        // Usamos la tabla 'tareas' como proxy de proyectos
        if ($db->tableExists('tareas')) {
            // Total
            $bTotal = $db->table('tareas');
            if (!empty($filters['from'])) $bTotal->where('fecha_limite >=', $filters['from']);
            if (!empty($filters['to'])) $bTotal->where('fecha_limite <=', $filters['to']);
            if (!empty($filters['asignado'])) $bTotal->where('asignado_a', $filters['asignado']);
            $kpis['total_tareas'] = $bTotal->countAllResults();

            // Pendientes (case-insensitive)
            $bPend = $db->table('tareas');
            if (!empty($filters['from'])) $bPend->where('fecha_limite >=', $filters['from']);
            if (!empty($filters['to'])) $bPend->where('fecha_limite <=', $filters['to']);
            if (!empty($filters['asignado'])) $bPend->where('asignado_a', $filters['asignado']);
            $bPend->where('UPPER(estado)', 'PENDIENTE');
            $kpis['pendientes'] = $bPend->countAllResults();

            // Completados (case-insensitive, admite COMPLETADA/COMPLETADO)
            $bComp = $db->table('tareas');
            if (!empty($filters['from'])) $bComp->where('fecha_limite >=', $filters['from']);
            if (!empty($filters['to'])) $bComp->where('fecha_limite <=', $filters['to']);
            if (!empty($filters['asignado'])) $bComp->where('asignado_a', $filters['asignado']);
            $bComp->groupStart()
                 ->where('UPPER(estado)', 'COMPLETADA')
                 ->orWhere('UPPER(estado)', 'COMPLETADO')
                 ->groupEnd();
            $kpis['completados'] = $bComp->countAllResults();
        }

        if ($db->tableExists('trabajadores')) {
            $kpis['empleados'] = $db->table('trabajadores')->countAllResults();
        }

        return $kpis;
    }

    public function getRendimientoEmpleados(array $filters = []): array
    {
        // Cuenta de tareas por asignado_a
        $db = \Config\Database::connect();
        if (!$db->tableExists('tareas')) return [];
        $builder = $db->table('tareas')
            ->select('asignado_a, COUNT(*) as total')
            ->groupBy('asignado_a')
            ->orderBy('total', 'DESC')
            ->limit(6);
        if (!empty($filters['from'])) $builder->where('fecha_limite >=', $filters['from']);
        if (!empty($filters['to'])) $builder->where('fecha_limite <=', $filters['to']);
        if (!empty($filters['asignado'])) $builder->where('asignado_a', $filters['asignado']);
        $rows = $builder->get()->getResultArray();
        return $rows ?: [];
    }

    public function getEstadoProyectos(array $filters = []): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('tareas')) return [];
        $builder = $db->table('tareas')->select('estado, COUNT(*) as total')->groupBy('estado');
        if (!empty($filters['from'])) $builder->where('fecha_limite >=', $filters['from']);
        if (!empty($filters['to'])) $builder->where('fecha_limite <=', $filters['to']);
        if (!empty($filters['asignado'])) $builder->where('asignado_a', $filters['asignado']);
        $rows = $builder->get()->getResultArray();
        return $rows ?: [];
    }


    public function getResponsables(array $filters = []): array
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('tareas')) return [];
        $builder = $db->table('tareas')->select('DISTINCT asignado_a as nombre')->orderBy('nombre','ASC');
        if (!empty($filters['from'])) $builder->where('fecha_limite >=', $filters['from']);
        if (!empty($filters['to'])) $builder->where('fecha_limite <=', $filters['to']);
        $rows = $builder->get()->getResultArray();
        return $rows ?: [];
    }
}

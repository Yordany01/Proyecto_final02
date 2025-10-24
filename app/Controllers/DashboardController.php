<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DashboardModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('dashboard/index');
    }

    public function kpis()
    {
        $model = new DashboardModel();
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'asignado' => $this->request->getGet('asignado')
        ];
        return $this->response->setJSON(['success'=>true, 'data'=>$model->getKpis($filters)]);
    }

    public function rendimientoEmpleados()
    {
        $model = new DashboardModel();
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'asignado' => $this->request->getGet('asignado')
        ];
        return $this->response->setJSON(['success'=>true, 'data'=>$model->getRendimientoEmpleados($filters)]);
    }

    public function estadoProyectos()
    {
        $model = new DashboardModel();
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'asignado' => $this->request->getGet('asignado')
        ];
        return $this->response->setJSON(['success'=>true, 'data'=>$model->getEstadoProyectos($filters)]);
    }

    

    public function responsables()
    {
        $model = new DashboardModel();
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
        ];
        return $this->response->setJSON(['success'=>true, 'data'=>$model->getResponsables($filters)]);
    }
}

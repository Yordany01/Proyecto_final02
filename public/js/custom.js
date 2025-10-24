$.sidebarMenu = function (menu) {
	var animationSpeed = 300;

	$(menu).on("click", "li a", function (e) {
		var $this = $(this);
		var checkElement = $this.next();

		if (checkElement.is(".treeview-menu") && checkElement.is(":visible")) {
			checkElement.slideUp(animationSpeed, function () {
				checkElement.removeClass("menu-open");
			});
			checkElement.parent("li").removeClass("active");
		}

		//If the menu is not visible
		else if (
			checkElement.is(".treeview-menu") &&
			!checkElement.is(":visible")
		) {
			//Get the parent menu
			var parent = $this.parents("ul").first();
			//Close all open menus within the parent
			var ul = parent.find("ul:visible").slideUp(animationSpeed);
			//Remove the menu-open class from the parent
			ul.removeClass("menu-open");
			//Get the parent li
			var parent_li = $this.parent("li");

			//Open the target menu and add the menu-open class
			checkElement.slideDown(animationSpeed, function () {
				//Add the class active to the parent li
				checkElement.addClass("menu-open");
				parent.find("li.active").removeClass("active");
				parent_li.addClass("active");
			});
		}
		//if this isn't a link, prevent the page from being redirected
		if (checkElement.is(".treeview-menu")) {
			e.preventDefault();
		}
	});
};
$.sidebarMenu($(".sidebar-menu"));

// Custom Sidebar JS
jQuery(function ($) {
	//toggle sidebar
	$(".toggle-sidebar").on("click", function () {
		$(".page-wrapper").toggleClass("toggled");
	});

	// Pin sidebar on click
	$(".pin-sidebar").on("click", function () {
		if ($(".page-wrapper").hasClass("pinned")) {
			// unpin sidebar when hovered
			$(".page-wrapper").removeClass("pinned");
			$("#sidebar").unbind("hover");
		} else {
			$(".page-wrapper").addClass("pinned");
			$("#sidebar").hover(
				function () {
					console.log("mouseenter");
					$(".page-wrapper").addClass("sidebar-hovered");
				},
				function () {
					console.log("mouseout");
					$(".page-wrapper").removeClass("sidebar-hovered");
				}
			);
		}
	});

	// Pinned sidebar
	$(function () {
		$(".page-wrapper").hasClass("pinned");
		$("#sidebar").hover(
			function () {
				console.log("mouseenter");
				$(".page-wrapper").addClass("sidebar-hovered");
			},
			function () {
				console.log("mouseout");
				$(".page-wrapper").removeClass("sidebar-hovered");
			}
		);
	});

	// Toggle sidebar overlay
	$("#overlay").on("click", function () {
		$(".page-wrapper").toggleClass("toggled");
	});

	// Added by Srinu
	$(function () {
		// When the window is resized,
		$(window).resize(function () {
			// When the width and height meet your specific requirements or lower
			if ($(window).width() <= 768) {
				$(".page-wrapper").removeClass("pinned");
			}
		});
		// When the window is resized,
		$(window).resize(function () {
			// When the width and height meet your specific requirements or lower
			if ($(window).width() >= 768) {
				$(".page-wrapper").removeClass("toggled");
			}
		});
	});
});

/***********
***********
***********
	Bootstrap JS 
***********
***********
***********/

// Tooltip
var tooltipTriggerList = [].slice.call(
	document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Popover
var popoverTriggerList = [].slice.call(
	document.querySelectorAll('[data-bs-toggle="popover"]')
);
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	return new bootstrap.Popover(popoverTriggerEl);
});

// Avatar change handler (shared for perfil.php and perfil_empleado.php)
document.addEventListener('DOMContentLoaded', function(){
  var avatarFile = document.getElementById('avatarFile');
  var avatarPreview = document.getElementById('avatarPreview');
  var removeAvatarBtn = document.getElementById('removeAvatarBtn');
  var avatarAlert = document.getElementById('avatarAlert');

  if (!avatarPreview) return; // Only run on pages that have avatar UI

  // Determine storage key (scope per page/role)
  var isEmployee = (typeof window.APP_ROLE === 'string' && window.APP_ROLE.toLowerCase().includes('empleado')) ||
                   (location.pathname || '').toLowerCase().indexOf('perfil_empleado.php') !== -1;
  var storageKey = isEmployee ? 'zoe.avatar.employee' : 'zoe.avatar.admin';

  // Default/original image
  var originalSrc = avatarPreview.getAttribute('data-default-src') || avatarPreview.src;
  if (!avatarPreview.getAttribute('data-default-src')) {
    avatarPreview.setAttribute('data-default-src', originalSrc);
  }

  function showAlert(el){
    if(!el) return;
    el.classList.remove('d-none');
    setTimeout(function(){ el.classList.add('d-none'); }, 2000);
  }

  // Restore from localStorage on load
  try {
    var saved = localStorage.getItem(storageKey);
    if (saved) {
      avatarPreview.src = saved;
    }
  } catch(e) {}

  // Handle file selection: convert to base64 and persist
  if (avatarFile) {
    avatarFile.addEventListener('change', function(){
      var file = this.files && this.files[0] ? this.files[0] : null;
      if (!file) return;
      var reader = new FileReader();
      reader.onload = function(e){
        var dataUrl = e.target.result;
        avatarPreview.src = dataUrl;
        try { localStorage.setItem(storageKey, dataUrl); } catch(err) {}
        showAlert(avatarAlert);
      };
      reader.readAsDataURL(file);
    });
  }

  // Remove: clear storage and restore default
  if (removeAvatarBtn) {
    removeAvatarBtn.addEventListener('click', function(){
      try { localStorage.removeItem(storageKey); } catch(e) {}
      avatarPreview.src = originalSrc;
      if (avatarFile) { try { avatarFile.value = ''; } catch(e){} }
      showAlert(avatarAlert);
    });
  }
});

// Mobile-friendly sidebar and search behavior (shared across pages)
document.addEventListener('DOMContentLoaded', function(){
  try {
    var body = document.body;
    var toggleBtn = document.getElementById('sidebarToggle');
    var backdrop = document.querySelector('.sidebar-backdrop');
    var sidebar = document.getElementById('sidebar');

    function closeSidebar(){
      body.classList.remove('sidebar-open');
      document.documentElement.classList.remove('sidebar-frozen');
    }
    function toggleSidebar(){
      body.classList.toggle('sidebar-open');
      const isOpen = body.classList.contains('sidebar-open');
      document.documentElement.classList.toggle('sidebar-frozen', isOpen);
    }

    if (toggleBtn) toggleBtn.addEventListener('click', function(e){ e.preventDefault(); toggleSidebar(); });
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    if (sidebar) {
      sidebar.querySelectorAll('a').forEach(function(a){
        a.addEventListener('click', function(){ if (window.innerWidth < 992) closeSidebar(); });
      });
    }

    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
    window.addEventListener('resize', function(){ if (window.innerWidth >= 992) closeSidebar(); });

    var searchContainer = document.getElementById('searchContainer');
    if (searchContainer) {
      var lastScrollTop = 0;
      window.addEventListener('scroll', function(){
        var st = window.pageYOffset || document.documentElement.scrollTop;
        var hide = st > lastScrollTop && st > 100;
        if (hide) searchContainer.classList.add('hide-search');
        else searchContainer.classList.remove('hide-search');
        lastScrollTop = st <= 0 ? 0 : st;
      }, { passive: true });
    }
  } catch(e) { /* no-op */ }
});

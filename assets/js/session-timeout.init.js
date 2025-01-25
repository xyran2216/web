/*
Template Name: Skote - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Session Timeout Js File
*/

$.sessionTimeout({
	keepAliveUrl: '?keep-alive',
	logoutButton:'Logout',
	logoutUrl: `logout?target=${window.location.href}`,
	redirUrl: `logout?target=${window.location.href}`,
	warnAfter: 100000,
	redirAfter: 10000,
	countdownMessage: 'Redirecting in {timer} seconds.'
});

$('#session-timeout-dialog  [data-dismiss=modal]').attr("data-bs-dismiss", "modal");
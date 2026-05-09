(function () {
  const scripts = [
    "../MEDECIN/shared/doctor-sidebar.js",
    "../MEDECIN/dashboard-medecin/dashboard-medecin.js"
  ];

  scripts.forEach((src) => {
    const script = document.createElement("script");
    script.src = src;
    script.defer = true;
    document.head.appendChild(script);
  });
})();

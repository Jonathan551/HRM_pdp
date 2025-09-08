function initDashboardCharts(avgNilai, dataKategori) {
  const ctxAvg = document.getElementById('avgChart').getContext('2d');
  new Chart(ctxAvg, {
    type: 'bar',
    data: {
      labels: ['Rata-rata'],
      datasets: [{
        label: 'Nilai',
        data: [avgNilai],
        backgroundColor: 'rgba(54,162,235,0.5)'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,   
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, max: 5 } }
    }
  });

  const ctxKat = document.getElementById('kategoriChart').getContext('2d');
  new Chart(ctxKat, {
    type: 'pie',
    data: {
      labels: dataKategori.map(x => x.nama),
      datasets: [{
        data: dataKategori.map(x => x.jumlah),
        backgroundColor: ['#2196F3','#4CAF50','#FFC107','#E91E63','#9C27B0']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,   
      plugins: { legend: { position: 'bottom' } }
    }
  });
}
// Auto-dismiss alerts after 4 seconds
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
    setTimeout(function() {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      bsAlert.close();
    }, 4000);
  });

  // Active nav highlight
  const currentPath = window.location.pathname;
  document.querySelectorAll('.sidebar-nav a').forEach(function(link) {
    if (currentPath.includes(link.getAttribute('href').replace(/^.*\//, ''))) {
      link.classList.add('active');
      link.style.background = '#2d6a4f';
      link.style.color = '#fff';
    }
  });
});

// Confirm delete helper
function confirmDelete(msg) {
  return confirm(msg || 'Are you sure you want to delete this record?');
}
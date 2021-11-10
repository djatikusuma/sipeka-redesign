

const swal = (message = 'default', icon = success) => {
  Swal.fire({
    text: message,
    icon: icon,
    buttonsStyling: false,
    confirmButtonText: "Ok",
    customClass: {
      confirmButton: "btn btn-primary"
    }
  });
}

const debounce = (func, wait, immediate) => {
  var timeout;
  return function () {
    var context = this,
      args = arguments;
    var later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

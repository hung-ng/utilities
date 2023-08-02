function changeHandler(events) {
  events.stopPropagation();
  events.preventDefault();
  let files = events.target.files;
  let file = files[0];
  let fileReader = new FileReader();
  fileReader.onload = function (progressEvent) {
    var url = fileReader.result;
    document.getElementById("image").src = url;
  };
  fileReader.readAsDataURL(file);
}

// initialize with defaults
$("#input-b7").fileinput({
  elErrorContainer: '#kartik-file-errors',
  allowedFileExtensions: ["json"],
  uploadUrl: '/load.php'
});
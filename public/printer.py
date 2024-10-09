import tempfile
import win32api
import win32print

filename = tempfile.mktemp (".txt")
open (filename, "w").write ("hello world python")
win32api.ShellExecute (
  0,
  "printto",
  filename,
  '"%s"' % win32print.GetDefaultPrinter (),
  ".",
  0
)

print('hello word python')

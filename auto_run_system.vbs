' auto_run_php_silent.vbs
Option Explicit

Dim objShell, projectName, localhostURL

' Set your project folder name here
projectName = "e-wallet-transactions-recording-system"  ' <-- change to your folder name

' URL to open
localhostURL = "http://localhost/" & projectName & "/"

' Create shell object
Set objShell = CreateObject("WScript.Shell")

' Open default browser with project URL silently (0 = hidden window)
objShell.Run "cmd /c start """" """ & localhostURL & """", 0, False

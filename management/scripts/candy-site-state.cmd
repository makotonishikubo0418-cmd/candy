@echo off
call "%~dp0candy-python.cmd" site-state %*
exit /b %ERRORLEVEL%

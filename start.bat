@echo off

:START
powershell.exe -ExecutionPolicy Bypass -File "local-start.ps1"
if %errorlevel% equ 0 (
    echo Access granted
    pause
    goto END
) else (
    echo Access denied. Please try again.
    pause
    goto START
)

:END
echo Closing application...
timeout /t 2 >nul
exit /b

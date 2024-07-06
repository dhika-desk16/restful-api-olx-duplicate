$expectedUsername = "admin"
$expectedPassword = "1234"

do {
    $username = Read-Host "-u" -AsSecureString 
    $password = Read-Host "-p" -AsSecureString

    $plainUsername = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($username))
    $plainPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))

    if ($plainUsername -eq $expectedUsername -and $plainPassword -eq $expectedPassword) {
        Write-Host "Success"
        $ipAddress = Get-NetIPAddress -InterfaceAlias "Wi-Fi 2" | Where-Object { $_.AddressFamily -eq "IPv4" } | Select-Object -ExpandProperty IPAddress
        php artisan serve --host=$ipAddress --port=80
    }
    else {
        Write-Host "Try Again"
    }
} while ($true)

$expectedUsername = "admin"
$expectedPassword = "1234"
$delaySeconds = 2

function Test-ConnectionToIP {
    param (
        [string]$ipAddress
    )
    $ping = Test-Connection -ComputerName $ipAddress -Count 1 -Quiet -ErrorAction SilentlyContinue
    return $ping
}

function Get-ActiveIPAddress {
    param (
        [string[]]$interfaceAliases,
        [int]$delaySeconds
    )
    foreach ($alias in $interfaceAliases) {
        Write-Host "Trying to access $alias..."
        $interface = Get-NetAdapter -Name $alias -ErrorAction SilentlyContinue
        if ($interface -and $interface.Status -eq "Up") {
            $ipAddress = Get-NetIPAddress -InterfaceAlias $alias -ErrorAction SilentlyContinue | Where-Object { $_.AddressFamily -eq "IPv4" } | Select-Object -ExpandProperty IPAddress
            if ($ipAddress) {
                return $ipAddress
            }
        }
        Start-Sleep -Seconds $delaySeconds
    }
    return $null
}
do {
    $username = Read-Host "-u" -AsSecureString 
    $password = Read-Host "-p" -AsSecureString

    $plainUsername = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($username))
    $plainPassword = [System.Runtime.InteropServices.Marshal]::PtrToStringAuto([System.Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))

    if ($plainUsername -eq $expectedUsername -and $plainPassword -eq $expectedPassword) {
        Write-Host "Success"
        
        $interfaceAliases = @("Wi-Fi", "Wi-Fi 2", "Wi-Fi 3")
        $ipAddress = Get-ActiveIPAddress -interfaceAliases $interfaceAliases -delaySeconds $delaySeconds

        if ($ipAddress) {
            Write-Host "Using IP address: $ipAddress"
            php artisan serve --host=$ipAddress --port=80
        }
        else {
            Write-Host "Wi-Fi not connected"
            Write-Host "1 : default settings"
            Write-Host "2 : manual configuration"

            $runOption = Read-Host "run-option"
            
            if ($runOption -eq '1') {
                Write-Host "Running with default"
                php artisan serve
            }
            elseif ($runOption -eq '2') {
                Write-Host "Running with manual"
                $ipAddress = Read-Host "ip address "
                $port = Read-Host "port "
                php artisan serve --host=$ipAddress --port=$port
            }
            else {
                Write-Host "Invalid option"
            }
        }
    }
    else {
        Write-Host "Try Again"
    }
} while ($true)

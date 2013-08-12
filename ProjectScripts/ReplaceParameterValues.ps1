function ReplaceInFiles($targetDirectory, $old, $new) {
    Get-ChildItem $targetDirectory -Recurse |
    Where {$_ -IS [IO.FileInfo]} |
    % {
        Set-Content $_.FullName ((Get-Content $_.FullName) -replace $old,$new)
        Write-Host "Processed: " + $_.FullName
    }
}
$appName = Read-Host "ApplicationName?"
$adminPassword = Read-Host "Admin Password?"
$inetPassword = Read-Host "Inet Password?"

ReplaceInFiles 'files' '__appName__' $appName
ReplaceInFiles 'files' '__adminPassword__' $adminPassword
ReplaceInFiles 'files' '__inetPassword__' $inetPassword

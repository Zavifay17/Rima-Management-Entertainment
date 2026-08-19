Add-Type -AssemblyName System.Drawing

$src = "C:\Users\LENOVO\.gemini\antigravity-ide\brain\d83e2959-fdd1-4688-8bef-f1f538a5b798\media__1787141757926.png"
$img = [System.Drawing.Image]::FromFile($src)
$w = $img.Width
$h = $img.Height

$size = [math]::Max($w, $h) + 10

$bmp = New-Object System.Drawing.Bitmap $size, $size
$g = [System.Drawing.Graphics]::FromImage($bmp)
$g.Clear([System.Drawing.Color]::Transparent)

$dst_x = [int](($size - $w) / 2)
$dst_y = [int](($size - $h) / 2)

$g.DrawImage($img, $dst_x, $dst_y, $w, $h)

$bmp.Save("c:\laragon\www\RMEEE\landingpage\public\favicon.png", [System.Drawing.Imaging.ImageFormat]::Png)
$bmp.Save("c:\laragon\www\RMEEE\admin\public\favicon.png", [System.Drawing.Imaging.ImageFormat]::Png)

$g.Dispose()
$bmp.Dispose()
$img.Dispose()

Write-Output "Done"

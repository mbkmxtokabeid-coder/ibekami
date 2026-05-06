<?php

namespace App\Livewire;

use Livewire\Component;

class Footer extends Component
{
    public string $companyName = 'IBEKAMI';
    public string $companyFullName = 'Ikhtiar Berkah Ekonomi Kreatif Asli Medan Indonesia';
    public string $email = 'ibeka1011@gmail.com';
    public string $whatsappNumber = '628170769999';
    public string $instagramHandle = '@ibekami.id';
    public string $tiktokHandle = '@ibekami.id';
    
    // Address
    public string $addressLine1 = 'KOMPLEK SETIA BUDI POINT';
    public string $addressLine2 = 'Jl. Setia Budi No.D-10, Tj. Sari';
    public string $addressLine3 = 'Kec. Medan Selayang, Kota Medan';
    public string $addressLine4 = 'Sumatera Utara 20132';
    
    // Google Maps Embed URL
    public string $mapsEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.2642997320017!2d98.63692687455165!3d3.562946096411253!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30312f8411773ac5%3A0x3a6f109b483f3e2a!2sDigital%20Printing%20Ikhtiar%20Berkah%20Acrylic%20Akrilik%2C%20Plakat%2C%20Tumbler%2C%20dan%20Souvenir%20merchandise%20gimik!5e1!3m2!1sid!2sid!4v1744685839326!5m2!1sid!2sid';
    
    // Operating Hours
    public string $operatingDays = 'Senin – Sabtu';
    public string $operatingHours = '08:30 – 17:00 WIB';
    public string $closedDays = 'Hari Libur / Nasional';

    public function render()
    {
        return view('livewire.footer');
    }
}

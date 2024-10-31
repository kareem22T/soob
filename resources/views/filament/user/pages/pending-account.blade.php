<x-filament-panels::page>
@if (session('success'))
    <div style="text-align: center;background: #88c273;padding: 10px;border-radius: 10px;color: #fff;font-size: 18px;font-weight: 700;">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div style="text-align: center;background: #ff4e4e;padding: 10px;border-radius: 10px;color: #fff;font-size: 18px;font-weight: 700;">{{ session('error') }}</div>
@endif

<h1 style="text-align: center;font-weight: 800;font-size: 20px;color: #536493;">Your account is pending approval. <br> An admin is reviewing your account, and we will notify you once it is approved</h1>
<svg xmlns="http://www.w3.org/2000/svg" style="width: 150px;height: 150px;margin: auto;stroke: #88C273;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2"> <path d="M20.942 13.018a9 9 0 1 0 -7.909 7.922"></path> <path d="M12 7v5l2 2"></path> <path d="M17 17v5"></path> <path d="M21 17v5"></path> </svg>
</x-filament-panels::page>

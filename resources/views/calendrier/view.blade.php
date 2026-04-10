@extends('dashboard')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 80vh; padding: 20px;">
    <div id="calendar-wrapper">
        <!-- Header -->
        <div id="calendar-header">
            <div class="header-left">
                <h2 id="month-title"></h2>

                <!-- Formulaire Desktop -->
                <form id="desktop-form" method="GET" action="{{ route('calendrierDespo.search') }}" 
                      class="flex items-center gap-2 desktop-form" novalidate>
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="date" id="date_debut" name="disponibilite_debut" value="{{ request('disponibilite_debut') }}"
                           class="rounded-md border border-c-border py-2 px-2 text-sm bg-transparent focus:ring-main focus:border-main">
                    <span class="text-sm">au</span>
                    <input type="date" id="date_fin" name="disponibilite_fin" value="{{ request('disponibilite_fin') }}"
                           class="rounded-md border border-c-border py-2 px-2 text-sm bg-transparent focus:ring-main focus:border-main">

                    <div class="p-2 flex gap-2">
                        <button type="submit" class="px-3 py-2 rounded-md bg-main text-primary text-sm hover:bg-main-dark">Valider</button>
                        <div class="relative inline-block group">
                            <a href="javascript:void(0);" id="reset-icon" class="w-min p-0 hover:bg-main/10 flex items-center justify-center px-4 py-2 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-main-dark">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                                </svg>
                            </a>
                            <span class="absolute left-1/2 transform backdrop-blur-sm -translate-x-1/2 bottom-full mb-2 opacity-0 translate-y-2 transition-all group-hover:opacity-100 group-hover:translate-y-0 bg-main/10 text-main text-sm rounded py-1 px-2 whitespace-nowrap">Réinitialiser</span>
                        </div>
                    </div>
                </form>

                <!-- Navigation -->
                <div class="fc-buttons">
                    <button id="prev-btn">←</button>
                    <button id="next-btn">→</button>
                </div>

                <!-- Hamburger -->
                <button id="hamburger-btn" class="hamburger-btn mobile-only">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="mobile-menu">
            <form id="mobile-form" method="GET" action="{{ route('calendrierDespo.search') }}" 
                  class="flex flex-col gap-3 p-4" novalidate>
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="date" id="mobile_date_debut" name="disponibilite_debut" value="{{ request('disponibilite_debut') }}"
                       class="rounded-md border border-c-border py-2 px-3 text-sm bg-transparent focus:ring-main focus:border-main w-full">
                <span class="text-sm self-center">au</span>
                <input type="date" id="mobile_date_fin" name="disponibilite_fin" value="{{ request('disponibilite_fin') }}"
                       class="rounded-md border border-c-border py-2 px-3 text-sm bg-transparent focus:ring-main focus:border-main w-full">

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-3 py-2 rounded-md bg-main text-primary text-sm hover:bg-main-dark">Valider</button>
                    <a href="javascript:void(0);" id="mobile-reset-icon" class="px-4 py-2 rounded-md hover:bg-main/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-main-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Calendar Table -->
        <table id="calendar-table">
            <thead>
                <tr>
                    <th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th>
                </tr>
            </thead>
            <tbody id="calendar-body"></tbody>
        </table>

        <!-- POPUP -->
        <div id="availability-popup" onclick="if(event.target.id === 'availability-popup') hideAvailabilityPopup();">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Détails des modèles disponibles.</h3>
                    <button onclick="hideAvailabilityPopup()" class="close-btn">×</button>
                </div>
                <div id="popup-models" class="popup-body"></div>
                <div class="popup-footer">
                    <button onclick="hideAvailabilityPopup()" class="px-3 py-2 rounded-md bg-main text-primary text-sm hover:bg-main-dark">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Wrapper et transition */
    #calendar-body { transition: opacity 0.2s ease; min-height: 420px; }

    #calendar-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        width: 100%; 
        max-width: 100%;
        padding: 1.5rem;
        box-sizing: border-box;
    }

    #month-title { 
        color:#000 !important; 
        font-size: 1.9rem; 
        font-weight: bold; 
        margin: 0; 
    }

    #calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.2rem;
        gap: 15px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        flex: 1;
    }

    .desktop-form {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .hamburger-btn {
        display: none;
        flex-direction: column;
        gap: 4px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px 10px;
    }

    .hamburger-btn span {
        width: 26px;
        height: 3px;
        background: #111;
        border-radius: 2px;
    }

    .mobile-menu {
        display: none;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        margin: 10px 0 15px 0;
        box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        padding: 16px;
    }

    .mobile-menu.show { display: block; }

    #calendar-header .fc-buttons {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }

    #calendar-header .fc-buttons button {
        color: #ffffff; 
        background: #000; 
        border: none;
        font-size: 1.9rem;
        padding: 4px 12px;
        cursor: pointer;
        line-height: 1;
        border-radius: 5px;
    }

    /* Calendar Table */
    #calendar-table { 
        width: 100%; 
        border-collapse: collapse; 
        table-layout: fixed; 
    }

    #calendar-table th, #calendar-table td {
        width: 14.28%; /* 100% / 7 colonnes */
        height: 82px; 
        text-align: center; 
        vertical-align: top;
        padding: 4px 2px; 
        cursor: pointer; 
        border-radius: 8px; 
        position: relative; 
        overflow: visible;
        font-size: 15px;
    }

    #calendar-table th { 
        font-weight: 600; 
        background: #f8f8f8; 
        padding: 30px 0; 
    }

    .day-cell { position: relative; overflow: visible; }

    .day-cell span {
        position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        z-index: 10; font-weight: 600; color: black; pointer-events: none;
    }

    .day-cell .availability-bar {
        position: absolute;
        left: 0; right: 0;
        top: 56px;
        height: 25px;
        background-color: rgba(20, 116, 20, 0.25);
        z-index: 2;
        cursor: pointer;
        overflow: hidden;
        font-size: 14px;
        font-weight: 700;
        color: #145214;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        text-align: center;
        line-height: 1;
    }

    .day-cell.availability-start .availability-bar { border-top-left-radius: 10px; border-bottom-left-radius: 10px; left: 4px; }
    .day-cell.availability-end .availability-bar { border-top-right-radius: 10px; border-bottom-right-radius: 10px; right: 4px; }
    .day-cell.availability-start.availability-end .availability-bar { left: 4px; right: 4px; border-radius: 10px; }

    .day-cell .bar-text {
        display: none;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 95%;
    }
    .day-cell.availability-center .bar-text { display: block; }

    /* POPUP */
    #availability-popup {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    #availability-popup.show { display: flex; }

    #availability-popup .popup-content {
        background: #fff;
        border-radius: 12px;
        width: 90%;
        max-width: 520px;
        max-height: 85vh;
        box-shadow: 0 15px 40px rgba(0,0,0,0.35);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #availability-popup .popup-header {
        padding: 1.4rem 1.8rem;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    #availability-popup .popup-header h3 {
        margin: 0;
        font-size: 1.35rem;
        color: #000;
        font-weight: 600;
    }

    #availability-popup .close-btn {
        background: none;
        border: none;
        font-size: 32px;
        cursor: pointer;
        color: #666;
        padding: 0;
        width: 36px;
        height: 36px;
        line-height: 1;
    }

    #availability-popup .popup-body {
        padding: 1.8rem;
        overflow-y: auto;
        flex: 1;
    }

    #availability-popup .popup-footer {
        padding: 1.4rem 1.8rem;
        border-top: 1px solid #eee;
        text-align: right;
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
        #calendar-wrapper { padding: 1rem; }
        #month-title { font-size: 1.2rem; }

        .hamburger-btn { display: flex; }
        .desktop-form { display: none; }

        .header-left { flex-wrap: nowrap; }

        #calendar-header .fc-buttons { margin-left: 0; }

        #calendar-table th, #calendar-table td { height: 68px; font-size: 14px; }
        .day-cell .availability-bar {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            font-size: 12px;
            padding-left: 16px;
            justify-content: flex-start !important;
            text-align: left !important;
            height: 25px;
        }

        #calendar-header .fc-buttons button {
            color: #000; 
            background: transparent; 
        }
    }

   @media (max-width: 768px) {
        #calendar-table {
        table-layout: auto;
    }
    #calendar-table th, #calendar-table td { 
        width: auto; 
        height: 62px; 
        padding: 6px 4px; 
    }

    #calendar-table th { 
        font-weight: 600; 
        background: #f8f8f8; 
        padding: 30px 0; 
    }
    }

   .circle-day-black span {
    display: inline-block;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    text-align: center;
    box-sizing: border-box;
    background-color: #dcdcdc; 
    line-height: 32px;
    }


</style>
@endsection
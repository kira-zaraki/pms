# 🏨 PMS & Channel Manager
### Enterprise-Grade Property Management System built with Laravel 12 & Filament v5

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-3.x-F59E0B)
![License](https://img.shields.io/badge/license-MIT-green)

---

## 📖 Overview
PMS is a professional-grade hospitality engine. Beyond simple room management, it features a **state-aware Channel Manager** that synchronizes Availability, Rates, and Inventory (ARI) via iCal protocols with major OTAs (Airbnb, Booking.com, VRBO).

---

## 🛠 Architectural Highlights

### 1. The Sync Engine (Manager Pattern)
The Channel Manager is built on a **Provider-Agnostic Architecture**. 
- **Inbound Sync**: A queued Job (`SyncIcalReservations`) parses remote iCal feeds using a non-blocking parser.
- **Outbound Sync**: Dynamic iCal generation via signed routes, ensuring OTAs always have real-time availability.
- **Conflict Resolution**: Uses `external_id` mapping to perform UPSERT operations, preventing overbookings and duplicate records during high-frequency syncs.

### 2. High-Performance UI (Filament v5)
The admin interface utilizes **Filament's Modular Schemas** to separate form logic from resource definitions, making the codebase highly maintainable.

---

## 🗄 Database Architecture

### Key Entities:
- **Rooms**: Supports hierarchical numbering, floor management, and real-time status (`Available`, `Occupied`, `Maintenance`).
- **Reservations**: Polymorphic-ready design supporting `internal` and `ical_sync` sources.
- **Guests**: Unified guest profiles regardless of booking source.
- **Galleries**: Optimized spatie-media-library style image management for room marketing.

---

## 🚀 Installation & Professional Setup

### System Requirements
- PHP 8.2 or higher
- Redis (Recommended for Queue Management)
- Supervisor (To manage the `queue:work` process)

### Step-by-Step
1. **Clone & Install**
   ```bash
   git clone https://github.com
   composer install && npm install
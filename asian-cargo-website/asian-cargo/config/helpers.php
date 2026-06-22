<?php
/**
 * Shared helper functions
 */

require_once __DIR__ . '/database.php';

/** Escape output for safe HTML display */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Fetch a single setting value by key, with optional fallback */
function setting(string $key, string $default = ''): string
{
    static $cache = null;

    if ($cache === null) {
        $cache = [];
        $stmt = getDB()->query('SELECT setting_key, setting_value FROM settings');
        foreach ($stmt->fetchAll() as $row) {
            $cache[$row['setting_key']] = $row['setting_value'];
        }
    }

    return $cache[$key] ?? $default;
}

/** Generate a new unique tracking number, e.g. AC2026LHE7321 */
function generateTrackingNumber(string $originCity): string
{
    $db = getDB();
    $cityCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $originCity) . 'XXX', 0, 3));
    $year = date('Y');

    do {
        $random = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $trackingNumber = "AC{$year}{$cityCode}{$random}";

        $stmt = $db->prepare('SELECT id FROM shipments WHERE tracking_number = ?');
        $stmt->execute([$trackingNumber]);
    } while ($stmt->fetch());

    return $trackingNumber;
}

/** Human-readable label + color class for a shipment status */
function statusInfo(string $status): array
{
    $map = [
        'booked'             => ['label' => 'Booked',              'class' => 'status-booked'],
        'picked_up'          => ['label' => 'Picked Up',           'class' => 'status-transit'],
        'in_transit'         => ['label' => 'In Transit',          'class' => 'status-transit'],
        'arrived_hub'        => ['label' => 'Arrived at Hub',      'class' => 'status-transit'],
        'customs_clearance'  => ['label' => 'Customs Clearance',   'class' => 'status-warning'],
        'out_for_delivery'   => ['label' => 'Out for Delivery',    'class' => 'status-transit'],
        'delivered'          => ['label' => 'Delivered',           'class' => 'status-success'],
        'delayed'            => ['label' => 'Delayed',             'class' => 'status-warning'],
        'exception'          => ['label' => 'Exception',           'class' => 'status-danger'],
    ];

    return $map[$status] ?? ['label' => ucfirst($status), 'class' => 'status-booked'];
}

/** Ordered list of all statuses used to draw the progress tracker */
function statusSteps(): array
{
    return [
        'booked'            => 'Booked',
        'picked_up'         => 'Picked Up',
        'in_transit'        => 'In Transit',
        'arrived_hub'       => 'Arrived at Hub',
        'customs_clearance' => 'Customs Clearance',
        'out_for_delivery'  => 'Out for Delivery',
        'delivered'         => 'Delivered',
    ];
}

function serviceLabel(string $service): string
{
    $map = [
        'air_freight'     => 'Air Freight',
        'ocean_freight'   => 'Ocean Freight',
        'express_courier' => 'Express Courier',
        'road_freight'    => 'Road Freight',
    ];

    return $map[$service] ?? ucfirst(str_replace('_', ' ', $service));
}

function formatDate(?string $datetime, string $format = 'd M Y, h:i A'): string
{
    if (!$datetime) {
        return '—';
    }
    return date($format, strtotime($datetime));
}

/** Redirect helper */
function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

/** Simple flash messages stored in session */
function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }

    return null;
}

/** CSRF token helpers */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrfToken()) . '">';
}

function csrfVerify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

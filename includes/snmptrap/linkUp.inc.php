<?php

$interface = dbFetchRow("SELECT * FROM `ports` WHERE `device_id` = ? AND `ifIndex` = ?", array($device['device_id'],$who));

if (!$interface) {
    exit;
}

$ifOperStatus = trim(strstr($entry[6], " "));
$ifAdminStatus = trim(strstr($entry[5], " "));

log_event("SNMP Trap: linkUp $ifAdminStatus/$ifOperStatus " . $interface['ifDescr'], $device, "interface", 1, $interface['port_id']);

if ($ifAdminStatus != $interface['ifAdminStatus']) {
    log_event("Interface Enabled : " . $interface['ifDescr'] . " (TRAP)", $device, "interface", 3, $interface['port_id']);
    dbUpdate(array('ifAdminStatus' => 'up'), 'ports', 'port_id=?', array($interface['port_id']));
}

if ($ifOperStatus != $interface['ifOperStatus']) {
    log_event("Interface went Up : " . $interface['ifDescr'] . " (TRAP)", $device, "interface", 1, $interface['port_id']);
    dbUpdate(array('ifOperStatus' => 'up'), 'ports', 'port_id=?', array($interface['port_id']));
}

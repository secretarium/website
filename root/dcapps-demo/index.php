<?php
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if(preg_match("/\.secretarium\.(com|dev|org)$/", $http_origin)) {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    header('Content-type:application/json;charset=utf-8');
?>
{
    "madrec": {
        "name": "madrec",
        "display": "MADRec",
        "description": "Massive Anonymous Data Reconciliation. Collectively measure reference data quality.",
		"icon": "fa-chart-pie",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
    },
    "sbx": {
        "name": "sbx",
        "display": "Crypto Exchange",
        "description": "Crypto currency order book to facilitate HTLCs (cross-chain atomic swaps)",
        "icon": "fa-exchange-alt",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
    },
    "cdnn": {
        "name": "cdnn",
        "display": "Machine Learning",
        "description": "IA calibration on confidential samples",
        "icon": "fa-brain",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
    },
    "fcp": {
        "name": "fcp",
        "display": "Fund Client Pooling",
        "description": "A tool to perform confidential statistics on fund distribution",
        "icon": "fa-grip-horizontal",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
    },
    "inconel": {
        "name": "inconel",
        "display": "Inconel",
        "description": "Real time consensus analytics and pricing for OTC market",
        "icon": "fa-chart-bar",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
    },
    "frtb": {
        "name": "frtb",
        "display": "FRTB Data Pooling",
        "description": "Collectively prove liquidity under FRTB regulation",
        "icon": "fa-compress",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
	},
	"me": {
		"name": "me",
		"display": "Personal Records",
		"description": "Get your personal records verified and become identifiable",
		"icon": "fa-id-card",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		],
		"loaded": true
	},
	"monitoring": {
		"name": "monitoring",
		"display": "Monitoring",
		"description": "Health and state of Secretarium networks",
		"icon": "fa-tasks",
		"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
		"network" : "sec-demo-1",
		"gateways": [
			{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
			{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH FR)" },
			{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH DE)" }
		]
	}
}
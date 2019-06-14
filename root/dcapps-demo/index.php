<?php
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    if(preg_match("/\.secretarium\.(com|dev|org)$/", $http_origin)) {
        header("Access-Control-Allow-Origin: $http_origin");
    }
    header('Content-type:application/json;charset=utf-8');
?>
{
	"clusters": {
		"sec-demo-1": {
			"gateways": [
				{ "endpoint": "wss://ovh1.node.secretarium.org:443/", "name": "DEMO (OVH UK)" },
				{ "endpoint": "wss://ovh2.node.secretarium.org:443/", "name": "DEMO (OVH DE)" },
				{ "endpoint": "wss://ovh3.node.secretarium.org:443/", "name": "DEMO (OVH FR-1)" },
				{ "endpoint": "wss://ovh4.node.secretarium.org:443/", "name": "DEMO (OVH FR-2)" }
			]
		}
	},
	"dcapps": {
		"madrec": {
			"name": "madrec",
			"display": "MADRec",
			"description": "Massive Anonymous Data Reconciliation. Collectively measure reference data quality.",
			"icon": "fa-chart-pie",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1",
			"ui": {
				"src": "/dcapps-demo/madrec.php",
				"require": [
					{ "name": "papaparse-4.6.0", "src": "/scripts/papaparse-4.6.0.min.js" },
					{ "name": "chart-2.8.0", "src": "/scripts/chart-2.8.0.min.js"},
					{ "name": "stream-saver-0.0.0", "src": "/scripts/stream-saver-0.0.0.js" },
					{ "name": "madrec-formats-0.0.15", "src": "/scripts/madrec.formats-0.0.15.js" }
				]
			}
		},
		"sbx": {
			"name": "sbx",
			"display": "Crypto Exchange",
			"description": "Crypto currency order book to facilitate HTLCs (cross-chain atomic swaps)",
			"icon": "fa-exchange-alt",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1"
		},
		"cdnn": {
			"name": "cdnn",
			"display": "Machine Learning",
			"description": "IA calibration on confidential samples",
			"icon": "fa-brain",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1"
		},
		"fcp": {
			"name": "fcp",
			"display": "Fund Client Pooling",
			"description": "A tool to perform confidential statistics on fund distribution",
			"icon": "fa-grip-horizontal",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1"
		},
		"inconel": {
			"name": "inconel",
			"display": "Inconel",
			"description": "Real time consensus analytics and pricing for OTC market",
			"icon": "fa-chart-bar",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1"
		},
		"frtb": {
			"name": "frtb",
			"display": "FRTB Data Pooling",
			"description": "Collectively prove liquidity under FRTB regulation",
			"icon": "fa-compress",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1"
		},
		"me": {
			"name": "me",
			"display": "Personal Records",
			"description": "Get your personal records verified and become identifiable",
			"icon": "fa-id-card",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1",
			"loaded": true
		},
		"monitoring": {
			"name": "monitoring",
			"display": "Monitoring",
			"description": "Health and state of Secretarium networks",
			"icon": "fa-tasks",
			"key": "rliD_CISqPEeYKbWYdwa-L-8oytAPvdGmbLC0KdvsH-OVMraarm1eo-q4fte0cWJ7-kmsq8wekFIJK0a83_yCg==",
			"cluster" : "sec-demo-1"
		}
	}
}
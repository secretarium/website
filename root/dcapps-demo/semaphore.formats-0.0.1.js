"use strict"

var Semaphore = (function() {

    /*DATA LISTS*/
    var ISO_3166_1_ALPHA_2 = [
            "AD","AE","AF","AG","AI","AL","AM","AO","AQ","AR","AS","AT","AU","AW","AX","AZ","BA","BB","BD","BE","BF","BG","BH","BI","BJ","BL","BM","BN",
            "BO","BQ","BR","BS","BT","BV","BW","BY","BZ","CA","CC","CD","CF","CG","CH","CI","CK","CL","CM","CN","CO","CR","CU","CV","CW","CX","CY","CZ",
            "DE","DJ","DK","DM","DO","DZ","EC","EE","EG","EH","ER","ES","ET","FI","FJ","FK","FM","FO","FR","GA","GB","GD","GE","GF","GG","GH","GI","GL",
            "GM","GN","GP","GQ","GR","GS","GT","GU","GW","GY","HK","HM","HN","HR","HT","HU","ID","IE","IL","IM","IN","IO","IQ","IR","IS","IT","JE","JM",
            "JO","JP","KE","KG","KH","KI","KM","KN","KP","KR","KW","KY","KZ","LA","LB","LC","LI","LK","LR","LS","LT","LU","LV","LY","MA","MC","MD","ME",
            "MF","MG","MH","MK","ML","MM","MN","MO","MP","MQ","MR","MS","MT","MU","MV","MW","MX","MY","MZ","NA","NC","NE","NF","NG","NI","NL","NO","NP",
            "NR","NU","NZ","OM","PA","PE","PF","PG","PH","PK","PL","PM","PN","PR","PS","PT","PW","PY","QA","RE","RO","RS","RU","RW","SA","SB","SC","SD",
            "SE","SG","SH","SI","SJ","SK","SL","SM","SN","SO","SR","SS","ST","SV","SX","SY","SZ","TC","TD","TF","TG","TH","TJ","TK","TL","TM","TN","TO",
            "TR","TT","TV","TW","TZ","UA","UG","UM","US","UY","UZ","VA","VC","VE","VG","VI","VN","VU","WF","WS","YE","YT","ZA","ZM","ZW"],
        ISO_3166_1_ALPHA_3 = [
            "AND","ARE","AFG","ATG","AIA","ALB","ARM","AGO","ATA","ARG","ASM","AUT","AUS","ABW","ALA","AZE","BIH","BRB","BGD","BEL","BFA","BGR","BHR",
            "BDI","BEN","BLM","BMU","BRN","BOL","BES","BRA","BHS","BTN","BVT","BWA","BLR","BLZ","CAN","CCK","COD","CAF","COG","CHE","CIV","COK","CHL",
            "CMR","CHN","COL","CRI","CUB","CPV","CUW","CXR","CYP","CZE","DEU","DJI","DNK","DMA","DOM","DZA","ECU","EST","EGY","ESH","ERI","ESP","ETH",
            "FIN","FJI","FLK","FSM","FRO","FRA","GAB","GBR","GRD","GEO","GUF","GGY","GHA","GIB","GRL","GMB","GIN","GLP","GNQ","GRC","SGS","GTM","GUM",
            "GNB","GUY","HKG","HMD","HND","HRV","HTI","HUN","IDN","IRL","ISR","IMN","IND","IOT","IRQ","IRN","ISL","ITA","JEY","JAM","JOR","JPN","KEN",
            "KGZ","KHM","KIR","COM","KNA","PRK","KOR","KWT","CYM","KAZ","LAO","LBN","LCA","LIE","LKA","LBR","LSO","LTU","LUX","LVA","LBY","MAR","MCO",
            "MDA","MNE","MAF","MDG","MHL","MKD","MLI","MMR","MNG","MAC","MNP","MTQ","MRT","MSR","MLT","MUS","MDV","MWI","MEX","MYS","MOZ","NAM","NCL",
            "NER","NFK","NGA","NIC","NLD","NOR","NPL","NRU","NIU","NZL","OMN","PAN","PER","PYF","PNG","PHL","PAK","POL","SPM","PCN","PRI","PSE","PRT",
            "PLW","PRY","QAT","REU","ROU","SRB","RUS","RWA","SAU","SLB","SYC","SDN","SWE","SGP","SHN","SVN","SJM","SVK","SLE","SMR","SEN","SOM","SUR",
            "SSD","STP","SLV","SXM","SYR","SWZ","TCA","TCD","ATF","TGO","THA","TJK","TKL","TLS","TKM","TUN","TON","TUR","TTO","TUV","TWN","TZA","UKR",
            "UGA","UMI","USA","URY","UZB","VAT","VCT","VEN","VGB","VIR","VNM","VUT","WLF","WSM","YEM","MYT","ZAF","ZMB","ZWE"],
        COUNTRIES_WITH_STATES = ["US", "USA"],
        STATES = {
            "US" : {
                "AL": "Alabama",
                "AK": "Alaska",
                "AS": "American Samoa",
                "AZ": "Arizona",
                "AR": "Arkansas",
                "CA": "California",
                "CO": "Colorado",
                "CT": "Connecticut",
                "DE": "Delaware",
                "DC": "District Of Columbia",
                "FM": "Federated States Of Micronesia",
                "FL": "Florida",
                "GA": "Georgia",
                "GU": "Guam",
                "HI": "Hawaii",
                "ID": "Idaho",
                "IL": "Illinois",
                "IN": "Indiana",
                "IA": "Iowa",
                "KS": "Kansas",
                "KY": "Kentucky",
                "LA": "Louisiana",
                "ME": "Maine",
                "MH": "Marshall Islands",
                "MD": "Maryland",
                "MA": "Massachusetts",
                "MI": "Michigan",
                "MN": "Minnesota",
                "MS": "Mississippi",
                "MO": "Missouri",
                "MT": "Montana",
                "NE": "Nebraska",
                "NV": "Nevada",
                "NH": "New Hampshire",
                "NJ": "New Jersey",
                "NM": "New Mexico",
                "NY": "New York",
                "NC": "North Carolina",
                "ND": "North Dakota",
                "MP": "Northern Mariana Islands",
                "OH": "Ohio",
                "OK": "Oklahoma",
                "OR": "Oregon",
                "PW": "Palau",
                "PA": "Pennsylvania",
                "PR": "Puerto Rico",
                "RI": "Rhode Island",
                "SC": "South Carolina",
                "SD": "South Dakota",
                "TN": "Tennessee",
                "TX": "Texas",
                "UT": "Utah",
                "VT": "Vermont",
                "VI": "Virgin Islands",
                "VA": "Virginia",
                "WA": "Washington",
                "WV": "West Virginia",
                "WI": "Wisconsin",
                "WY": "Wyoming"
            }
        },
        RISK_PROFILES = ["Low", "Medium", "High"],
        STATUS = ["Under review", "Approved", "Rejected", "Pending"],
        PEP_FLAGS = ["Directors", "Officers", "SMOs", "Other"],
        SANCTIONS = [
            "EU Consolidated List of Sanctions", "HM Treasury Sanctions List", "OFAC Sanctions List",
            "UN Sanctions", "Bank of England Sanctions List", "Other"];

    /*DATA REGEX*/
    var LEI = /^[0-9A-Z]{4}[0-9A-Z]{14}\d{2}$/,
        YN = /^(true|false|y|n)$/i;

    /*UTILS*/
    function ISO_7064_MOD_97_10(x) {
        let p = 0;
        for (let i = 0; i < x.length; i++) {
            let c = x.charCodeAt(i);
            p = c + (c >= 65 ? p * 100 - 55 : p * 10 - 48); /* 'A' is 65, '0' is 48 */
            if (p > 10000) p %= 97;
        }
        return p % 97;
    }

    /*VERIFIERS/TRANSFORMERS*/
    var verifiers = {
        "LEI": x => { /*ISO 17442*/
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            x = x.toUpperCase();
            let m = x.match(LEI);
            if (m == null)
                return { success: false, error: "format is incorrect (not ISO-17442)" };
            if (ISO_7064_MOD_97_10(m[0]) != 1)
                return { success: false, error: "incorrect check sum" };
            return { success: true, value: x };
        },
        "RISK_PROFILES": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            if(!RISK_PROFILES.includes(x))
                return { success: false, error: "is not in the list" };
            return { success: true };
        },
        "STATUS": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            if(!STATUS.includes(x))
                return { success: false, error: "is not in the list" };
            return { success: true };
        },
        "PEP_FLAGS": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            if(!PEP_FLAGS.includes(x))
                return { success: false, error: "is not in the list" };
            return { success: true };
        },
        "SANCTIONS": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            if(!SANCTIONS.includes(x))
                return { success: false, error: "is not in the list" };
            return { success: true };
        },
        "YN": x => {
            if(typeof x === "boolean")
                return { success: true, value: x ? "Y" : "N", warning: "boolean transformed to " + (x ? "'Y'" : "'N'") };
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(YN);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            if(x == "Y" || x == "N")
                return { success: true };
            let y = x.toUpperCase(), z = y == "TRUE" || y == "Y" ? "Y" : "N";
            return { success: true, value: z, warning: "'" + x + "' transformed to '" + z + "'" };
        },
        "CLEAN_STR": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            x = x.trim().replace(/\s+/gi, " ").replace(/[^\w\d\s]/gi, "").toUpperCase();
            return { success: true, value: x };
        },
        "COUNTRY": x => { /*ISO 3166-1-ALPHA-3*/
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            x = x.toUpperCase();
            if(x.length != 3)
                return { success: false, error: "format is incorrect" };
            if(!ISO_3166_1_ALPHA_3.includes(x))
                return { success: false, error: "'" + x + "' is not a valid country (ISO 3166-1 Alpha 3)" };
            return { success: true, value: x };
        }
    };

    return {
        fields: [
            { name: "Country", type: "list", values: ISO_3166_1_ALPHA_3, verifier: verifiers.COUNTRY, sample: "NER", required: true },
            { name: "Company Registration Number", type: "text", verifier: verifiers.CLEAN_STR, sample: "24325815", required: true },
            { name: "Client ID", type: "text", verifier: verifiers.CLEAN_STR, sample: "123456" },
            { name: "Client Risk Profile", type: "list", values: RISK_PROFILES, verifier: verifiers.RISK_PROFILES, sample: "" },
            { name: "Client Onboarding Status", type: "list", values: STATUS, verifier: verifiers.STATUS, sample: "" },
            { name: "Full Legal Name", type: "text", verifier: verifiers.CLEAN_STR, sample: "Unilever Finance International B.V." },
            { name: "LEI Code", type: "text", verifier: verifiers.LEI, sample: "549300LRI77T5F28OH18" },
            { name: "Politically Exposed Person", type: "bool", verifier: verifiers.YN, sample: "N" },
            { name: "PEP Reason", type: "list", values: PEP_FLAGS, verifier: verifiers.PEP_FLAGS, sample: "" },
            { name: "Sanctions", type: "bool", verifier: verifiers.YN, sample: "N" },
            { name: "Sanctions Reason", type: "list", values: SANCTIONS, verifier: verifiers.SANCTIONS, sample: "" },
            { name: "Watchlist", type: "bool", verifier: verifiers.YN, sample: "N" },
            { name: "Negative News", type: "bool", verifier: verifiers.YN, sample: "N" }
        ]
    };
})();
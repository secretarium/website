"use strict"

var MADRec = (function() {

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
        ELF = [
            "CDOV","CRL9","CYW2","GAJN","M27U","OLAI","P65N","PCRJ","PT6C","REUP","T5RL","UV8T","V905","YCV2","6W6X","BC38","J4JC","LZFR","PQHL",
            "Q82Q","R4KK","TXVC","XHCV","1NOX","5WWO","69H1","AAL7","AXSB","CAQ1","DM88","ECWU","EQOV","G3R6","GVPD","JQOI","JTAV","JUHG","NIJH",
            "O65B","ONF1","1TX8","2QSA","36KV","3LMA","3N94","4E5P","7SJP","8E2A","8YLB","B910","C609","CFH5","EDMK","EXY8","J5OU","JNAD","KLBO",
            "KM6O","LWHF","LWV6","N5NT","O59C","O5HQ","PVT3","QZIS","R85P","T922","TPTU","U2PN","U5KF","UCT9","UW1Y","V03J","V8L7","W3WH","WTLP",
            "X8ZK","XLEO","XQZX","Y1Q4","YBHM","ZOK2","ZSFX","ZUHK","1TX8","2QSA","36KV","3LMA","3N94","4E5P","7SJP","8E2A","8YLB","B910","C609",
            "CFH5","EDMK","EXY8","J5OU","JNAD","KLBO","KM6O","LWHF","LWV6","N5NT","O59C","O5HQ","PVT3","QZIS","R85P","T922","TPTU","U2PN","U5KF",
            "UCT9","UW1Y","V03J","V8L7","W3WH","WTLP","X8ZK","XLEO","XQZX","Y1Q4","YBHM","ZOK2","ZSFX","ZUHK","1TX8","2QSA","36KV","3LMA","3N94",
            "4E5P","7SJP","8E2A","8YLB","B910","C609","CFH5","EDMK","EXY8","J5OU","JNAD","KLBO","KM6O","LWHF","LWV6","N5NT","O59C","O5HQ","PVT3",
            "QZIS","R85P","T922","TPTU","U2PN","U5KF","UCT9","UW1Y","V03J","V8L7","W3WH","WTLP","X8ZK","XLEO","XQZX","Y1Q4","YBHM","ZOK2","ZSFX",
            "ZUHK","1A0A","3Y5C","4S4Z","4ZBE","51WZ","56MU","92JZ","AS4W","B2UK","DQMJ","FCPM","IPTQ","JFQ5","KSHL","NPFB","SNRI","VHIY","X088",
            "XL51","4IXL","771I","QK1F","Y2L3","946C","CTCH","EJ06","FPP2","HN63","PW3Y","S94S","SZW0","TCGV","U8LM","VJ3G","WTK4","WVIN","1EQC",
            "60MF","BASA","BH5D","C9KZ","DK7L","G65S","J3C5","K1QX","KVC1","R0KX","RHVP","RN4K","TRO6","TS9O","U938","1CML","23V9","AW80","F9J3",
            "HLBQ","LNBY","LRRN","O13B","OTU7","QODJ","S3K4","TGLV","WFPH","XOYI","YG5M","59T2","6TRA","D90J","M4IF","MGE0","UDLA","3Q15","56KZ",
            "6U2Q","7P8Y","B3RX","D8UQ","DHGI","EKRT","EPFN","GF6X","GLX2","IKMB","JBBW","M53U","MY4W","ZACY","85EC","TA98","VAPN","876U","8M03",
            "FQJ9","T0CV","TY0S","ULJ1","Y80K","2FHC","3M14","42GN","8OZ0","9XHE","GG0S","W8XG","1JGX","456Q","7VKC","BOYK","CMB6","EIDL","ESVK",
            "FBQL","HQKE","O90R","PCO0","3OVJ","5D9I","5EEA","7IKZ","7XZL","8KL5","FYYY","I8VP","JNCJ","KZDW","L2NN","LMSY","UWMY","94UQ","B69V",
            "PXPK","VHXN","5ONY","9D1K","FN6X","G9PJ","GM04","HBCT","LV28","MXLT","OPRT","RBBY","SS1V","Y485","1E9M","3Y5V","ASWX","EBYQ","ER0H",
            "GTBR","IKGM","SYPT","1ADH","CS15","KAM9","PIDT","QN8Y","QR4F","TPZ2","W126","59T2","6TRA","D90J","M4IF","MGE0","UDLA","40JO","44LR",
            "4M62","64XG","8MEC","AXW3","FGSL","GPG0","IDBS","IKSU","ILON","NONB","QJ3A","QNSC","TSQ7","WI0P","ZY4E","10UR","2U3G","814Y","A7KA",
            "CT3J","JVMD","QB2W","RQIC","T1LR","TWLR","UG3Z","YRUJ","Z6MF","24EG","2FED","3OW3","8VRU","9D9U","E9CM","EYKU","G5RH","IVVD","KP34",
            "LUMA","MF7P","NRTG","OQWO","YA9S","ZFQS","1SVL","1T06","2J66","36AO","5OZV","9E0Q","ADF8","IHD0","KM1T","MZB1","SS3A","WV3I","ZKTC",
            "1MBR","233U","8BL9","BCF5","FV0K","GLCH","HIL6","I8RE","INTY","K9L6","MYKG","P12B","RCPI","T1JS","U24X","W86L","1JK6","2DK3","2N9X",
            "3BX3","3G3D","3RMA","4A26","4OTK","4UB2","4YAK","5AP3","5KU5","6CQN","6FAI","6OMW","747U","74W6","7OZQ","84J8","8K5T","917C","95G8",
            "971Y","9HLU","9RVC","9XDB","AM3T","APEN","BL4B","C4Q2","C5FE","C7GZ","CATU","CD28","CHJR","CIO8","CUKQ","CZUA","D1VK","D35X","ET6Z",
            "FIPS","FY1B","G2I3","H4XQ","H6WW","HQPK","HS5W","HSNC","HY6K","I32F","I68R","IQ9O","J8PB","JCAD","JJYB","KM6Z","LEDI","LJ92","LJL0",
            "LPCQ","MAVU","MBUU","NI3I","NJ87","NPH3","NQHQ","O9PW","OVKW","P3YF","PFE5","PQJE","Q25I","QIEL","QJ0F","QJWK","QQ49","QS6A","R2XE",
            "RBGQ","RHFQ","RP0E","T1FT","T3Q1","TNBA","U95A","UFDA","V6YH","VIE3","VQU7","WJ30","WL83","WO0T","WUFK","WVZF","XG70","XRK7","Y18E",
            "Z3BF","ZQO8","ZU32","5QS7","7WRN","9KSX","FUKI","FW7S","GULL","H8VP","LWOD","NUL8","PZ6Y","TB2C","UA1O","WU7R","ZRPO","1NKP","3UPJ",
            "6I0P","752Q","8ZQE","9LJA","CKOS","GA3A","I1UP","J34T","JC0Y","LA47","LVEQ","PMOF","PRTB","RXTT","VSEV","157O","1B88","1GNM","1L24",
            "1NF1","1Q16","1Y0C","211B","29FA","2N4L","2NL6","2ONM","2WMP","2XIK","34QS","3AZY","3FCU","3NJL","3OK3","3S6E","3XTE","487E","491H",
            "4DZU","4H0F","4JS0","4RT7","4TNX","4VD7","59F9","5AJO","5H68","5K0Q","5VCF","61WE","66JQ","6CHY","6CJJ","6DYI","6IIE","6KWU","6RUE",
            "6SYJ","6YHU","74HH","7O94","7SZH","7UHA","82QO","87VO","8FZW","8G07","8GTU","8II5","8IWA","8LX5","8OKM","8R2G","8TYA","8VDW","8VWR",
            "8WH8","8XDK","94WO","97PZ","9HC8","9KU7","9N1F","9O0S","9T5S","A0VZ","AHBR","AMFI","B645","B7I7","BAK2","BAUQ","BDDP","BEWI","BRGA",
            "BYZF","C42J","C4D7","CAX5","CBO2","CF56","CNSS","CNXL","CQJI","CVH6","D2I2","DEG4","DRQM","DTAX","DWYD","DXQH","E0YF","E6YB","EABD",
            "EAC8","ECUL","EVYY","EYRI","F0B6","F6SZ","F72Z","F8UD","F93F","FBYB","FEEA","FFQL","FKG2","FOUY","FY0F","FYBN","G7Z2","G8LP","G98S",
            "GFN6","GLN8","GQV4","GXL6","GZCQ","H1U2","H3Z4","H3ZD","H4VR","HA3N","HATI","HC8N","HF2L","HMEI","HSYZ","I0MH","I8TE","IAP3","IC8G",
            "IC98","IF7H","IGSV","IQR2","J9OZ","JBIQ","JFET","JIYK","JM1W","JMO0","JN2P","JR7T","JU82","K39X","K5KA","K65D","K69X","K7XQ","K99N",
            "KBEQ","KCN5","KFR1","KMPN","KQH7","KS9B","KWI4","L25Z","L6QO","LARO","LGME","LU8S","LUIN","M1BJ","M673","MG2O","MMA4","MOUL","MQPZ",
            "MX8J","NBA8","NFCV","NM07","NOI8","NQCT","NUMY","OAGA","OIQA","OLJ1","OSLZ","OWUN","P3J5","P714","PESN","PL6V","PWKL","PZLG","Q2A1",
            "Q634","Q8E2","QCWO","QU3G","QUB4","QV2O","R0B6","R1JO","R59A","R6WQ","RD0Z","RFWH","RNJZ","RNSB","RT39","RT6Y","RWMC","S0X8","S82R",
            "SMZ6","SSHN","SWPX","SZ63","T25Y","TGRE","TNPO","TOL2","TPNT","TUE5","UFM7","UHKH","UQRY","UUT8","UV02","UZY3","V1Z5","V5SV","V6G1",
            "V9QP","VBC7","VBQP","VD7Z","VJV5","VO18","VTFS","W3LD","W4G1","WGCY","WXNU","X78I","XH8C","XK4T","XPX6","XQBA","Y1W1","Y4VA","Y7G8",
            "Y8WC","YADL","YHI5","YJM5","YUCL","YX4E","Z0JP","Z2FQ","Z5WG","Z9K8","ZECH","ZM5P","ZPEZ","ZQ9B","ZQIT","ZXHJ","ZZ0X","13AV","2HBR",
            "2YZO","40DB","63KS","6QQB","8CM0","8Z6G","JNDX","OL20","QZ3L","SGST","SUA1","T0YJ","US8E","4HD1","56NA","I0X7","J5RC","PH5T","T69Z",
            "VBJW","W9ZD","WP5I","XWPT","7U4R","CB98","GCCT","M4TI","QFLH","TKPE","UUYB","VJRL","WBKH","Y8CL","3RHO","54AL","CQ5X","H6OR","HKVP",
            "J3VJ","JYKN","L6IY","L8D6","MCWR","QCA0","QSYQ","TIZ2","UCU5","VKSR","W2NK","9XFK","BVOE","Q49K","UO3D","254M","85VD","E1SE","EPG7",
            "KSUS","N3VO","S2E3","UDG6","XPE5","254M","85VD","E1SE","EPG7","KSUS","N3VO","S2E3","UDG6","XPE5","4WV7","876R","8UEG","8VH3","995K",
            "BKUX","BMYJ","DN6F","DPY1","EO9F","HTJD","J6MO","M1DW","NZAI","P9F2","S3DA","TQ3O","TSVO","UD8K","X0SX","XW5U","Y64R","2OJP","8T79",
            "CMHQ","E4KB","M8SS","2GV9","363J","54SK","5AX8","9BPE","C58S","DWS3","FF1D","HNJK","JXDX","KMFX","LGWG","LMIM","LZIC","MNQ7","URQH",
            "ZJS8","VYAX","5AX8","C58S","FF1D","KMFX","LGWG","LMIM","LZIC","MNQ7","URQH","VYAX","1G26","5ABK","CDXK","EMLA","FI4M","FMPL","G4V6",
            "JHC4","M44Y","OESH","QZTT","SAI8","SP9F","TE79","TQ3Z","TV5P","VC3E","ZGPY","135L","2SZI","2XXH","5ODO","634M","70X0","8GU1","9KGS",
            "BL52","DCRK","DSSD","HN75","K021","KCHO","KCSG","LHBB","LZZD","NDEW","OV32","P418","QRZJ","T2X1","VA1V","VDRB","VUPE","W9SC","X62Z",
            "ZG6S","ZZOG","21F4","33IP","40A7","7RZH","JOX1","LO9A","SQXV","UBWU","1IPK","3LCY","AZTM","BS22","ES2D","VJBO","1DGT","1SOY","32HC",
            "7RRP","ANSR","BSZ8","IF49","K98U","NDID","TMU1","WAK8","Y8LH","13T9","4ZF3","5BY2","5CIE","5TA8","6JDN","81KA","A2GC","A338","BGPJ",
            "BPLS","DLCX","E4HU","F2TQ","F2ZF","HGMH","HJ1Y","K4GR","KWBA","LUGM","LUOA","NVPY","Q4A3","Q8UI","QG9Y","QRXD","SFK9","SFYA","UHNL",
            "WQRG","X8J8","YRXK","2IGL","2JEI","2S2U","5DWL","5GGB","63P9","68J6","81G5","9C91","ATQY","BEAN","BKAB","DVXS","JIWD","LCR0","SQ1A",
            "U8KA","UDY2","V19Y","V5OS","WCEP","ZFFA","BVJ0","C061","CR7N","JOZN","V89C","YB0U","BVJ0","C061","CR7N","JOZN","V89C","4AJY","6M6Z",
            "AOS4","B0LD","BVPF","G7H0","J76S","JWWT","RJYT","RTVE","S2J1","XXCZ","ZTUI","O786","33MN","4QXM","54M6","5WU6","62Y3","9AAK","A0W7",
            "B5PM","BBEB","CODH","DEO1","EZQW","GNXT","JHK5","L7HX","NFFH","UNJ2","V44D","Y3ZB","4LEA","F7KI","P2R9","Z6ZU","326Y","3C7U","3L58",
            "4ZRR","50TD","5ZTZ","8S9H","9DI1","AEV1","BJ65","CF5L","DRPL","EXD7","FSBD","GYY6","IQGE","K5P8","LJJW","M9IQ","O0EU","O7LB","PB3V",
            "Q0Q1","R71C","V06W","YI42","YTMC","ZQ0Q","4XMS","7IYW","88OX","QR25","RKYF","VQV6","XBK3","LH0Q","MOI8","13ZV","3BJG","5F76","60BG",
            "629I","6LUM","6OYI","8TOF","96XK","AL9T","BSJT","CY1M","EU0T","F21U","FANM","FJ0E","FQ5Y","GZE5","H7OD","HOT8","J3A3","JCKO","KM66",
            "LT9U","O7XB","OMX0","P1L1","QUX1","QYL4","RBHP","RUCO","SMIS","SP4S","SVA3","T7PB","WNX1","WOK7","WUJ2","YLZL","ZVVM","ZZKE","IKBN",
            "Z7ZX","1HGD","5KVH","68PD","6IK8","6L6P","99JD","A8CT","ALPT","D7OA","DFE5","IX01","KUUV","MFHR","N66B","NIQY","OXUC","P5S3","PIDC",
            "QFXD","USOG","VF4C","W9W3","XD16","XMXM","YMLD","Z0NE","ZILA","ZSWE","149O","DTIU","HEXO","JCK1","KHAB","KXDY","VPFY","XH7Z","1CDX",
            "37II","4V0A","5NCK","7B9H","7W9B","85K5","8FD2","AI01","ANDT","CTBD","CUGP","DUGD","EFBR","F0BK","F68H","FJQ8","GT4V","I15K","I9BB",
            "NFRJ","SWMK","TH8A","UWEE","V0FL","XHN1","15A2","17R7","1MJ0","1RJ2","1U7Y","24TU","2H36","2OTW","2R46","37YG","3AMJ","3EAX","3F98",
            "3TOV","3U8X","4PZX","4TYO","4VTZ","5VMR","6H83","8B9A","8JWV","8MQM","8NF7","8NZE","8RIH","8XDD","9RQZ","AS78","B5GH","BLVV","BQ3A",
            "BZQL","C3RQ","C7TI","CKTA","CN7I","D094","D84J","DO25","DZSY","E0VI","EH3M","EWQF","FWGK","FZKX","G2CW","GHC5","GQRJ","GWHK","GZF9",
            "H2AF","H2P4","H4NY","HKKL","HUET","HUR1","I9O1","I9V2","IBSS","IC46","ILY0","J18N","J7NW","JPMQ","JZBN","KLA2","KOYR","KVSH","KZC6",
            "LLL8","LYTH","MG22","MV1C","MVSF","NRK9","NZ85","O1KO","OCIS","OPLQ","OVLN","P9CF","PA5X","PDYW","PJRZ","Q9FA","QCRH","QFGM","QKFU",
            "QMEH","QQS1","QSXT","QUMQ","R71T","RJUK","SDVV","SIPP","TLDB","TMIG","UTBN","UY49","VA0A","VAB6","VE1P","VEOU","VEX5","VIGZ","VOUU",
            "WJXS","WYNO","XBRI","XIGI","XINV","XJJR","XNKN","YA7H","YGHZ","YJ6A","Z2AQ","Z3SH","Z3Y0","ZGYA","ZH3O","ZKAF","ZNHF","1RPE","7WL9",
            "BCM3","M6YY","QWFD","SXY3","TOMA","6UPM","73PZ","88TX","9O67","DEF6","E7W8","KIU5","YVPW","ZPOY","17FJ","2PHU","5SGV","KEMH","LWXI",
            "14NU","189Q","1CSW","1GLU","1HEP","1K73","1W8B","2EEG","2KMA","2XNE","3P3W","43MZ","46CD","4CKX","4GX1","5AM3","5J5Y","5YBL","5Z8W",
            "6R72","72R7","73MQ","7EG2","7O89","7QSE","8KHD","8RE3","8UE6","90YN","93WZ","95MS","9L9V","9NSV","AGSR","BKEB","BUMI","C4PZ","CS8B",
            "D18J","DAQC","E1VK","E4CI","E9SH","ED84","ER02","F560","FDVU","FMUC","FQRE","GL07","GY5R","HZG7","HZI2","I7AS","ISCZ","IW8Z","J8DW",
            "JKC5","KB52","KVQU","KWFE","KZ3R","L1M7","L9WT","MSGT","MY1S","N5MX","N7I1","N9QW","NC88","NL31","NLI7","NO20","O0Y2","O7TC","OKAZ",
            "P6XJ","PX29","QACV","QCJ9","R9GT","RF3D","S5E1","SIRY","SMUS","SPIE","SQ4I","SQFW","STUP","SUH3","T0KQ","TAV3","TG3X","U3QQ","U5IM",
            "U7QK","UCTO","VATN","VRJP","VSC1","VSZS","WQCR","XLXU","Y1X5","Y45Q","YB59","YF99","YGN4","YRIU","YVE8","YVWB","ZG2I","1OR3","4A8T",
            "KBKD","OPDJ","SD81","WZ78","4YUU","CXWJ","GQVQ","J7L0","MZT6","R155","R59V","U89P","XE4Z","ZE81","1G29","1QU8","1SL4","1ZHJ","4S57",
            "5RDO","7U8O","956I","9FPZ","A97B","AJ9U","ARDP","AXS5","B0V5","BI3B","CUIH","DDES","DP3Q","FCJF","FH4R","GJL1","GJTL","HRQA","I2WU",
            "IAS6","IT6N","JB2M","JTV5","K0RI","K6L9","QMUM","R2L8","S0Z5","S6X7","SS0L","TDD5","UJ35","VFIU","XYGP","1TN0","2UAX","381R","54P7",
            "9YIP","AZTO","BEAY","BYQJ","C61P","CX05","G04R","M0Y0","O1QI","OJ9I","PDQ0","RLJO","RYFP","SSOM","WZDB","XJHM","1BL5","1RKS","2B81",
            "2JZ4","2WFG","2XJA","3EKS","54WI","5BEZ","7MNN","AZA0","BF9N","CQMY","DP2E","E0NE","FFTN","GP8M","H781","HX77","JB25","KJ9Q","L5DU",
            "M848","MRSY","MVII","OBFU","QSI2","R9TC","TL87","UNA9","VPRH","W6A7","XJOT","YRGM","1BL5","1RKS","2B81","2JZ4","2WFG","2XJA","3EKS",
            "54WI","5BEZ","7MNN","AZA0","BF9N","CQMY","DP2E","E0NE","FFTN","GP8M","H781","HX77","JB25","KJ9Q","L5DU","M848","MRSY","MVII","OBFU",
            "QSI2","R9TC","TL87","UNA9","VPRH","W6A7","XJOT","YRGM","1BL5","1RKS","2B81","2JZ4","2WFG","2XJA","3EKS","54WI","5BEZ","7MNN","AZA0",
            "BF9N","CQMY","DP2E","E0NE","FFTN","GP8M","H781","HX77","JB25","KJ9Q","L5DU","M848","MRSY","MVII","OBFU","QSI2","R9TC","TL87","UNA9",
            "VPRH","W6A7","XJOT","YRGM","1BL5","1RKS","2B81","2JZ4","2WFG","2XJA","3EKS","54WI","5BEZ","7MNN","AZA0","BF9N","CQMY","DP2E","E0NE",
            "FFTN","GP8M","H781","HX77","JB25","KJ9Q","L5DU","M848","MRSY","MVII","OBFU","QSI2","R9TC","TL87","UNA9","VPRH","W6A7","XJOT","YRGM",
            "JMQU","ONVA","R5UT","W2SQ","55MA","T61R","WJ0A","4GJI","57V7","B6ES","CDOT","H0PO","ID30","Q0M5","U6R9","Z0EY","ZQ6S","HHYT","5MNR",
            "78H5","A9AW","D8PB","FGVH","IMDT","IVQQ","N69M","O85W","P24O","QEEJ","QUJ5","RSWJ","S68T","ST2Q","UGE3","VKPN","1ASH","1ONH","37F5",
            "43LY","6ZAR","8OK2","9HKD","9I4Y","BQRL","DEMP","IYZI","LDBS","OXAZ","S2K8","T4OL","TBN1","UJ49","1QMT","358I","3DXJ","4LKE","5JAL",
            "6NI8","8QMN","ARON","CJ58","F99D","IHLF","JF6D","Q4F9","Q6EM","SIST","U94O","UFIY","UKX4","X0MP","X1EL","Y5L0","YJZ3","WY1B","52U0",
            "7VK5","95OK","AEJF","BTQ1","EZNQ","FZMT","ZD39","74LJ","CNQ3","IPGV","O35K","SQOE","TJ6V","VNIU","ZJPG"],
        EMIR = ["FC","NFC-","NFC+"];

    /*DATA REGEX*/
    var LEI = /^[0-9A-Z]{4}[0-9A-Z]{14}\d{2}$/,
        NACE_CLASS = /^\d{2}\.\d{2}$/,
        NACE_GROUP = /^\d{2}\.\d$/,
        NACE_DIVISION = /^\d{2}$/,
        BIC = /^[0-9A-Z]{4}([A-Z]{2})[0-9A-Z]{2}([0-9A-Z]{3})?$/,
        YN = /^(true|false|y|n)$/i,
        GK = /^(\d{6}|[1-9]\d{0,5})$/,
        PERMID = /^\d{10}$/,
        LEGAL_FORM = /^[0-9A-Z]{4}$/;

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
        "BIC": x => { /*ISO 9362:2014*/
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            x = x.toUpperCase();
            let m = x.match(BIC);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            if (!ISO_3166_1_ALPHA_2.includes(m[1]))
                return { success: false, error: "'" + m[1] + "' is not a valid country (ISO 3166-1 Alpha 2)" };
            if(x.length == 8)
                return { success: true, value: x + "XXX", warning: "should be 11 characters, 'XXX' appended" };
            return { success: true, value: x };
        },
        "EMIR": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            x = x.toUpperCase();
            if(x.length != 2 && x.length != 4)
                return { success: false, error: "format is incorrect" };
            if(!EMIR.includes(x))
                return { success: false, error: "is not in the list" };
            return { success: true, value: x };
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
        "GK": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(GK);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            if(x.length != 6)
                return { success: true, value: "0".repeat(6 - x.length) + x, warning: "should be 6 numbers, leading zeros added" };
            return { success: true };
        },
        "PERMID": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(PERMID);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            return { success: true };
        },
        "NACE_CLASS": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(NACE_CLASS);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            return { success: true };
        },
        "NACE_GRP": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(NACE_GROUP);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            return { success: true };
        },
        "NACE_DIV": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(NACE_DIVISION);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            return { success: true };
        },
        "CLEAN_STR": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            x = x.trim().replace(/\s+/gi, " ").replace(/[^\w\d\s]/gi, "").toUpperCase();
            return { success: true, value: x };
        },
        "LEGAL_FORM": x => {
            if(typeof x !== "string")
                return { success: false, error: "expecting a string" };
            let m = x.match(LEGAL_FORM);
            if (m == null)
                return { success: false, error: "format is incorrect" };
            if(!ELF.includes(x))
                return { success: false, error: "is not in the list" };
            return { success: true };
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
        lei: { name: "LEI Code", type: "text", verifier: verifiers.LEI, sample: "549300LRI77T5F28OH18" },
        fields: [
            { name: "BIC Code", type: "text", verifier: verifiers.BIC, sample: "BFOFNL2RXXX" },
            { name: "EMIR Classification", type: "list", values: EMIR, verifier: verifiers.EMIR },
            { name: "Investment Firm", type: "bool", verifier: verifiers.YN, sample: "N" },
            { name: "GK Code", type: "text", verifier: verifiers.GK },
            { name: "PermID Code", type: "text", verifier: verifiers.PERMID },
            { name: "NACE Class", type: "text", verifier: verifiers.NACE_CLASS, sample: "11.11" },
            { name: "NACE Group", type: "text", verifier: verifiers.NACE_GRP },
            { name: "NACE Division", type: "text", verifier: verifiers.NACE_DIV },
            { name: "Immediate Parent", type: "text", verifier: verifiers.LEI, sample: "549300TK7G7NZTVM1Z30" },
            { name: "Ultimate Parent", type: "text", verifier: verifiers.LEI, sample: "549300TK7G7NZTVM1Z30" },
            { name: "Full Legal Name", type: "text", verifier: verifiers.CLEAN_STR, sample: "Unilever Finance International B.V." },
            { name: "Legal Form", type: "list", values: ELF, verifier: verifiers.LEGAL_FORM, sample: "54M6" },
            { name: "Country of Operation", type: "list", values: ISO_3166_1_ALPHA_3, verifier: verifiers.COUNTRY, sample: "NER" },
            { name: "Country of Incorporation", type: "list", values: ISO_3166_1_ALPHA_3, verifier: verifiers.COUNTRY, sample: "NER" },
            { name: "Country of Asset Location", type: "list", values: ISO_3166_1_ALPHA_3, verifier: verifiers.COUNTRY, sample: "NER" },
            { name: "US Person", type: "bool", verifier: verifiers.YN, sample: "N" },
            { name: "Company Registration Number", type: "text", verifier: verifiers.CLEAN_STR, sample: "24325815" }
        ]
    };
})();
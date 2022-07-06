<?php

namespace App\Config;

enum EventType: string {
    case JEDNODENNI = "JD";
    case VICEDENNI = "VA";
    case LETNI_TABOR = "LT";
    case PRO_VEDOUCI = "PV";

}
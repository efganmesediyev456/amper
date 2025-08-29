<?php

namespace App\Services\Api\Products;

class ProductMonthPrice{

    public function calculateLoan($credit, $credit_month, $credit_procent)
    {
        if (!$credit || !$credit_month) {
            return ['error' => 'Credit amount and months are required'];
        }

        if ($credit_procent == 0) {

            $ayliq_odenish = $credit / $credit_month;
            $borcun_qaligi = $credit;
            $result = [];

            for ($i = 0; $i < $credit_month; $i++) {
                $borcun_qaligi -= $ayliq_odenish;
                $result[] = [
                    'month' => $i + 1,
                    'monthly_payment' => number_format(round($ayliq_odenish, 2), 2, '.', ''),
                    'principal' => number_format(round($ayliq_odenish, 2), 2, '.', ''),
                    'interest' => '0.00',
                    'remaining_debt' => number_format(round($borcun_qaligi, 2), 2, '.', '')
                ];
            }

            $result[] = [
                'month' => 'Total',
                'monthly_payment' => number_format(round($credit, 2), 2, '.', ''),
                'principal' => number_format(round($credit, 2), 2, '.', ''),
                'interest' => '0.00',
                'remaining_debt' => '0.00'
            ];

            return $result;
        }

        $month_procent = $credit_procent / 12 / 100;
        $moterize_ve_ustu = pow((1 + $month_procent), $credit_month);
        $formul_top = $month_procent * $moterize_ve_ustu;
        $formul_bottom = $moterize_ve_ustu - 1;
        $formul_k = $formul_top / $formul_bottom;
        $ayliq_odenish = $formul_k * $credit;

        $faiz_meblegi = ($credit * ($credit_procent / 12)) / 100;
        $esas_mebleg = $ayliq_odenish - $faiz_meblegi;
        $borcun_qaligi = $credit - $esas_mebleg;

        $result = [];
        for ($i = 0; $i < $credit_month; $i++) {
            $result[] = [
                'month' => $i + 1,
                'monthly_payment' => number_format(round($ayliq_odenish, 2), 2, '.', ''),
                'principal' => number_format(round($esas_mebleg, 2), 2, '.', ''),
                'interest' => number_format(round($faiz_meblegi, 2), 2, '.', ''),
                'remaining_debt' => number_format(round($borcun_qaligi, 2), 2, '.', '')
            ];

            $faiz_meblegi = $borcun_qaligi * $month_procent;
            $esas_mebleg = $ayliq_odenish - $faiz_meblegi;
            $borcun_qaligi -= $esas_mebleg;
        }

        return $result;
    }


    public function monthlyPayment($credit, $credit_month, $credit_procent)
    {
        if (!$credit || !$credit_month) {
            return ['error' => 'Credit and credit_month are required'];
        }

        if ($credit_procent == 0) {
            return number_format(round($credit / $credit_month, 2), 2, '.', '');
        }

        $month_procent = $credit_procent / 12 / 100;
        $moterize_ve_ustu = pow((1 + $month_procent), $credit_month);
        $formul_top = $month_procent * $moterize_ve_ustu;
        $formul_bottom = $moterize_ve_ustu - 1;
        $formul_k = $formul_top / $formul_bottom;
        $ayliq_odenish = $formul_k * $credit;

        return number_format(round($ayliq_odenish, 2), 2, '.', '');
    }
}
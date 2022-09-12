<?php

namespace App\Services;

class ChartDataService
{

    /**
     * generating dataset and labels for multi axis line chart
     *
     * @param  collection $data
     * @param  string $x_data_key
     * @param  array $y_data_keys
     * @return array
     */
    public function multiAxisLine($data, string $x_data_key, array $y_data_keys): array
    {

        $x_data = $data->pluck($x_data_key)->toArray();
        foreach ($y_data_keys as  $y_data_key) {
            $y_data[$y_data_key] = $data->pluck($y_data_key)->toArray();
        }

        return [
            'x_data' => $x_data,
            'y_data' => $y_data,
        ];
    }
}

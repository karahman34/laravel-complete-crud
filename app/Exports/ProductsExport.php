<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $limit;

    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function headings(): array
    {
        return [
            'id',
            'image',
            'name',
            'price',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('id', 'image', 'name', 'price', 'status', 'created_at', 'updated_at')
                        ->limit($this->limit)
                        ->get();
    }
}

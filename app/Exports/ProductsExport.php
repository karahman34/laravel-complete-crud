<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
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
            'name',
            'price',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    public function map($product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'status' => $product->status,
            'created_at' => $product->created_at->toDateTimeString(),
            'updated_at' => $product->updated_at->toDateTimeString(),
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::select('id', 'name', 'price', 'status', 'created_at', 'updated_at')
                        ->limit($this->limit)
                        ->get();
    }
}

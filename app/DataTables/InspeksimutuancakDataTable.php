<?php

namespace App\DataTables;

use App\Models\mutu_ancak;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InspeksimutuancakDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'inspeksimutuancak.action')
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(mutu_ancak $model): QueryBuilder
    {
        // dd($model->newQuery());

        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        // dd($this->builder());
        return $this->builder()

            ->setTableId('inspeksimutuancak-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        // dd('test');
        return [
            Column::computed('action')
                ->exportable(true)
                ->printable(true)
                ->width(60)
                ->addClass('text-center'),
            Column::make('id'),
            Column::make('estate'),
            Column::make('afdeling'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Inspeksimutuancak_' . date('YmdHis');
    }
}

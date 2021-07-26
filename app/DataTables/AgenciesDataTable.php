<?php

namespace App\DataTables;

use App\Models\Agencies;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AgenciesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'agencies.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Agency $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Agencies $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder();

    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Agencies_' . date('YmdHis');
    }
}

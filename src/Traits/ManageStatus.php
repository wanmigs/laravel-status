<?php
namespace Fligno\User\Traits;

trait ManageStatus
{
    public function activate($id)
    {
        (new $this->model)->find($id)->activate();
    }

    public function deactivate($id)
    {
        (new $this->model)->find($id)->deactivate();
    }

    public function toggleStatus($id)
    {
        (new $this->model)->find($id)->toggleStatus();
    }

    public function bulkStatusUpdate()
    {
        (new $this->model)
            ->whereIn('id', request('ids'))
            ->update(['is_active' => request('status')]);
    }
}

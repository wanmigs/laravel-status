<?php
namespace Fligno\User\Traits;

trait HasStatus
{
    public function activate()
    {
        $this->forceFill(['is_active' => true])->save();
    }

    public function deactivate()
    {
        $this->forceFill(['is_active' => false])->save();
    }

    public function toggleStatus()
    {
        $this->forceFill(['is_active' => ! $this->is_active])->save();
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }
}

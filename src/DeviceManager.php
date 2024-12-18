<?php
namespace Hikvision;

class DeviceManager
{
    /**
     * Device provider
     *
     * @var Device
     *
     * Holds an instance of the `Device` class, which provides the connection details
     * and methods to interact with a Hikvision device.
     */
    protected $device;

    /**
     * Set device provider
     *
     * This method assigns an instance of the `Device` class to the `$device` property.
     * It is used to define which device the manager will control or communicate with.
     *
     * @param Device $device An instance of the `Device` class.
     * @return void
     */
    public function setDevice(Device $device)
    {
        $this->device = $device;
    }

    /**
     * Get device provider
     *
     * This method retrieves the `Device` instance currently assigned to the `$device` property.
     * It allows access to the device's details and functionality.
     *
     * @return Device The assigned instance of the `Device` class.
     */
    public function getDevice(): Device
    {
        return $this->device;
    }
}

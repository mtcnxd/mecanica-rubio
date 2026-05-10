<?php

namespace App\Services;

use App\Models\Employee;
use App\Traits\Messenger;

class EmployeeService
{
    use Messenger;

    public function getAll()
    {
        $employees = Employee::all();

        if ($employees->isEmpty()){
            throw new \Exception("Aun no existen empleados registraron");
        }

        return $employees;
    }

    public function getEmployeeById($id)
    {
        $employee = Employee::find($id);

        if (!$employee){
            throw new \Exception("No se encontro el empleado");
        }

        return $employee;
    }
}
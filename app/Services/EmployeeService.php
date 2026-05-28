<?php

namespace App\Services;

use App\Models\Employee;
use App\Traits\Notificator;

class EmployeeService
{
    use Notificator;

    public function getAll()
    {
        $employees = Employee::all();

        if ($employees->isEmpty()){
            throw new \Exception("Aun no existen empleados registraron");
        }

        return $employees;
    }

    public function find($id)
    {
        $employee = Employee::find($id);
        $employee->with('user');

        if (!$employee){
            throw new \Exception("No se encontro el empleado");
        }

        return $employee;
    }

    public function store(array $data) : bool
    {
        $data['start_date'] = now();
        
        Employee::create($data);

        return true;
    }
}
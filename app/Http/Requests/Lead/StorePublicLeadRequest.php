<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StorePublicLeadRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     * Al ser un link público, lo dejamos en true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación aplicadas a la solicitud.
     */
    public function rules(): array
    {
        return [
            // Datos básicos del Lead
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:leads,email'],
            'phone' => ['required', 'string', 'max:20'],
            
            // Datos del CSV y lógica de edad
            'birth_date_text' => ['required', 'date', 'before:today'],
            'address_full' => ['required', 'string', 'max:500'],
            'occupation' => ['required', 'string', 'max:255'],
            'social_media_handle' => ['nullable', 'string', 'max:255'],
            'medical_notes_lead' => ['nullable', 'string', 'max:1000'],
            
            // Validación condicional para Menores de Edad (Tutor)
            'parent_name' => [$this->isMinor() ? 'required' : 'nullable', 'string', 'max:255'],
            'parent_phone' => [$this->isMinor() ? 'required' : 'nullable', 'string', 'max:20'],
            'parent_relationship' => [$this->isMinor() ? 'required' : 'nullable', 'in:Padre,Madre,Tutor,Otro'],
            'parent_occupation' => [$this->isMinor() ? 'required' : 'nullable', 'string', 'max:255'],

            // Selección del curso y comprobante
            'course_offering_id' => ['required', 'exists:course_offerings,id'],
            'student_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'payment_receipt' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // Obligatorio y manual
        ];
    }

    /**
     * Calcula si es menor de edad basándose en la fecha enviada.
     */
    private function isMinor(): bool
    {
        if (!$this->has('birth_date_text')) return false;
        
        try {
            // Comprobamos si tiene menos de 18 años
            return Carbon::parse($this->input('birth_date_text'))->age < 18;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function messages(): array
    {
        return [
            'birth_date_text.required' => 'La fecha de nacimiento es necesaria para determinar el tipo de curso.',
            'parent_name.required' => 'Al ser menor de edad, los datos del tutor son obligatorios.',
            'payment_receipt.required' => 'Debe adjuntar el comprobante de pago para procesar su inscripción.',
            'email.unique' => 'Ya existe una solicitud con este correo electrónico.',
        ];
    }
}
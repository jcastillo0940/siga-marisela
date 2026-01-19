<?php

namespace App\Http\Controllers;

use App\DTOs\Lead\CreateLeadDTO;
use App\DTOs\Lead\UpdateLeadDTO;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Models\CourseOffering; // Importante para los selectores
use App\Services\LeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Para manejo de archivos

class LeadController extends Controller
{
    public function __construct(
        private LeadService $leadService
    ) {}

    public function index()
    {
        $leads = $this->leadService->getAllLeads();
        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        
        // Solución al error: Cargamos las ofertas para el dropdown de creación
        $courseOfferings = CourseOffering::with('course')
            ->where('is_active', true)
            ->get();

        return view('leads.create', compact('users', 'courseOfferings'));
    }

    public function store(StoreLeadRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Manejo de Archivos: Foto de la alumna
            if ($request->hasFile('student_photo')) {
                $data['student_photo'] = $request->file('student_photo')->store('leads/photos', 'public');
            }

            // Manejo de Archivos: Comprobante de pago
            if ($request->hasFile('payment_receipt')) {
                $data['payment_receipt_path'] = $request->file('payment_receipt')->store('leads/payments', 'public');
                $data['payment_status'] = 'pending';
            }

            $dto = CreateLeadDTO::fromRequest($data);
            $this->leadService->createLead($dto);

            DB::commit();

            return redirect()
                ->route('leads.index')
                ->with('success', 'Lead creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al crear el lead: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $lead = $this->leadService->getLeadById($id);

        if (!$lead) {
            return redirect()
                ->route('leads.index')
                ->with('error', 'Lead no encontrado');
        }

        return view('leads.show', compact('lead'));
    }

    public function edit(int $id)
    {
        $lead = $this->leadService->getLeadById($id);
        $users = User::where('is_active', true)->get();
        
        // Solución al error: Cargamos las ofertas para el dropdown de edición
        $courseOfferings = CourseOffering::with('course')
            ->where('is_active', true)
            ->get();

        if (!$lead) {
            return redirect()
                ->route('leads.index')
                ->with('error', 'Lead no encontrado');
        }

        return view('leads.edit', compact('lead', 'users', 'courseOfferings'));
    }

    public function update(UpdateLeadRequest $request, int $id)
    {
        DB::beginTransaction();

        try {
            $lead = Lead::findOrFail($id);
            $data = $request->validated();

            // Actualizar Foto
            if ($request->hasFile('student_photo')) {
                if ($lead->student_photo) Storage::disk('public')->delete($lead->student_photo);
                $data['student_photo'] = $request->file('student_photo')->store('leads/photos', 'public');
            }

            // Actualizar Comprobante
            if ($request->hasFile('payment_receipt')) {
                if ($lead->payment_receipt_path) Storage::disk('public')->delete($lead->payment_receipt_path);
                $data['payment_receipt_path'] = $request->file('payment_receipt')->store('leads/payments', 'public');
            }

            $dto = UpdateLeadDTO::fromRequest($data);
            $this->leadService->updateLead($id, $dto);

            DB::commit();

            return redirect()
                ->route('leads.show', $id)
                ->with('success', 'Lead actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el lead: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $lead = Lead::findOrFail($id);
            
            // Limpiar archivos antes de borrar el registro
            if ($lead->student_photo) Storage::disk('public')->delete($lead->student_photo);
            if ($lead->payment_receipt_path) Storage::disk('public')->delete($lead->payment_receipt_path);

            $this->leadService->deleteLead($id);

            return redirect()
                ->route('leads.index')
                ->with('success', 'Lead eliminado exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el lead: ' . $e->getMessage());
        }
    }

    public function convertToStudent(int $id, Request $request)
    {
        try {
            $student = $this->leadService->convertToStudent($id, $request->get('additional_data', []));

            return redirect()
                ->route('students.show', $student->id)
                ->with('success', 'Lead convertido a estudiante exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al convertir el lead: ' . $e->getMessage());
        }
    }

    public function verifyPayment(Request $request, Lead $lead)
    {
        if (!auth()->user()->hasPermission('leads.edit')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string'
        ]);

        try {
            $this->leadService->verifyPayment(
                $lead->id, 
                $validated['status'], 
                $validated['notes']
            );

            $message = $validated['status'] === 'verified' 
                ? 'Pago verificado. Lead convertido en estudiante automáticamente.' 
                : 'El pago ha sido rechazado.';

            return redirect()->route('leads.show', $lead->id)->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar la verificación: ' . $e->getMessage());
        }
    }
}
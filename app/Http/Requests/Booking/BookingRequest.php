<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rooms' => 'required|array|min:1',
            'rooms.*.room_id' => 'required|exists:rooms,id',
            'rooms.*.check_in' => 'required|date|after_or_equal:today',
            'rooms.*.check_out' => 'required|date|after:check_in',
        ];
    }

    public function messages()
    {
        return [
            'rooms.*.room_id.required' => __('validation.required', ['attribute' => 'Phòng']),
            'rooms.*.room_id.exists' => __('validation.room_id.exists'),
            'rooms.*.check_in.required' => __('validation.required', ['attribute' => 'Ngày bắt đầu']),
            'rooms.*.check_in.date' => __('validation.check_in.date'),
            'rooms.*.check_in.after_or_equal' => __('validation.check_in.after_or_equal', ['attribute' => 'Ngày bắt đầu']),
            'rooms.*.check_out.required' => __('validation.required', ['attribute' => 'Ngày kết thúc']),
            'rooms.*.check_out.date' => __('validation.check_out.date'),
            'rooms.*.check_out.after' => __('validation.check_out.after', ['attribute' => 'Ngày bắt đầu']),
        ];
    }
}

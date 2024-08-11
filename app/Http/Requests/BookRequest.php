<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'title' => 'required',
            'summary' => 'required',
            'images' => 'required',
            'stock'  => 'required',
            'category_id' => 'required',
        ];
    } 
    // public function messages()
    // {
    //     return [
    //         'title.required' => 'title postingan harus diisi.',
    //         'title.max' => 'jumlah karakter title maksimal 255',
    //         'summary.required' => 'Summery harus diisi.',
    //         'stock.required' => 'stock harus Di isi',
    //         'category_id' => 'category Harus Di isi',
    //         'images.mimes' =>'images Harus Berupa jpg,bmp,png',
            
    //     ];
    // }
}

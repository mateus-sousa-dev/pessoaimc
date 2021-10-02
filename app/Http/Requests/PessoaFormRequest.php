<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PessoaFormRequest extends FormRequest
{
    private $camposValidacao;
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

        $this->camposValidacao = [
            'nome' => ['required', 'max:150'],
            'sexo' => ['required', 'max:1'],
            'peso' => ['required', 'max:6'],
            'altura' => ['required', 'max:4']
        ];

        $this->mensagensValidacao = [
            'nome.required' => 'Campo nome é obrigatório.',
            'sexo.required' => 'Campo sexo é obrigatório.',
            'peso.required' => 'Campo peso é obrigatório.',
            'altura.required' => 'Campo altura é obrigatório.',
            'nome.max' => 'Campo nome não pode exceder o limite de 150 caracteres.',
            'sexo.max' => 'Campo sexo inválido.',
            'peso.max' => 'Campo peso não pode exceder o limite de 6 caracteres.',
            'altura.max' => 'Campo altura não pode exceder o limite de 4 caracteres.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() === 'POST') {
            $this->camposValidacao['nome'][] = 'unique:pessoas,nome';
        }
        return $this->camposValidacao;
    }

    public function messages()
    {
        if ($this->method() === 'POST') {
            $this->mensagensValidacao['nome.unique'] = 'Pessoa com este nome já cadastrada.';
        }
        return $this->mensagensValidacao;
    }
}

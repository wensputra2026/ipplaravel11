@props([
    'name',
    'endpoint',
    'placeholder' => 'Cari data...',
    'multiple' => false,
    'value' => null,
    'selectedText' => null,
    'debounce' => 300,
    'id' => null,
])

@php
    $id = $id ?? 'tom_select_' . str_replace(['[', ']', '.'], '_', $name) . '_' . uniqid();
    $isMultiple = filter_var($multiple, FILTER_VALIDATE_BOOLEAN);
    
    // Normalize initial value & text for single vs multiple select
    $initialOptions = [];
    $initialItems = [];

    if (!empty($value)) {
        if ($isMultiple && (is_array($value) || is_string($value))) {
            $values = is_array($value) ? $value : json_decode($value, true) ?? explode(',', $value);
            $texts = is_array($selectedText) ? $selectedText : (is_string($selectedText) ? json_decode($selectedText, true) ?? explode(',', $selectedText) : []);
            
            foreach ($values as $index => $val) {
                $val = trim((string)$val);
                if ($val !== '') {
                    $text = isset($texts[$index]) ? trim((string)$texts[$index]) : $val;
                    $initialOptions[] = ['value' => $val, 'text' => $text];
                    $initialItems[] = $val;
                }
            }
        } else {
            $initialOptions[] = [
                'value' => (string)$value,
                'text' => (string)($selectedText ?? $value)
            ];
            $initialItems[] = (string)$value;
        }
    }
@endphp

<div 
    wire:ignore 
    x-data="tomSelectAjax({
        id: '{{ $id }}',
        name: '{{ $name }}',
        endpoint: '{{ $endpoint }}',
        placeholder: '{{ addslashes($placeholder) }}',
        isMultiple: {{ $isMultiple ? 'true' : 'false' }},
        debounce: {{ (int)$debounce }},
        initialOptions: {{ Js::from($initialOptions) }},
        initialItems: {{ Js::from($initialItems) }}
    })" 
    x-init="initSelect()" 
    class="w-full relative"
>
    <select 
        id="{{ $id }}"
        name="{{ $name }}{{ $isMultiple ? '[]' : '' }}"
        {{ $attributes->merge(['class' => 'tom-select-element w-full']) }}
        {{ $isMultiple ? 'multiple' : '' }}
        x-ref="selectElement"
    >
        @foreach($initialOptions as $opt)
            <option value="{{ $opt['value'] }}" selected>{{ $opt['text'] }}</option>
        @endforeach
    </select>
</div>

@once
@push('scripts')
<script>
const registerTomSelectAjax = () => {
    if (window.Alpine && !window._tomSelectAjaxRegistered) {
        window._tomSelectAjaxRegistered = true;
        Alpine.data('tomSelectAjax', (config) => ({
            instance: null,
            
            initSelect() {
                if (typeof TomSelect === 'undefined') {
                    console.error('TomSelect library is not loaded. Please ensure TomSelect JS & CSS are included.');
                    return;
                }

                const plugins = {
                    clear_button: { title: 'Hapus pilihan' }
                };

                if (config.isMultiple) {
                    plugins.remove_button = { title: 'Hapus item' };
                }

                this.instance = new TomSelect(this.$refs.selectElement, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['text', 'value'],
                    placeholder: config.placeholder,
                    options: config.initialOptions || [],
                    items: config.initialItems || [],
                    maxItems: config.isMultiple ? null : 1,
                    plugins: plugins,
                    loadThrottle: config.debounce || 300,
                    closeAfterSelect: !config.isMultiple,
                    preload: 'focus',
                    
                    load: function(query, callback) {
                        const url = new URL(config.endpoint, window.location.origin);
                        url.searchParams.set('q', query || '');

                        fetch(url.toString(), {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const results = Array.isArray(data) ? data : (data.data || []);
                            callback(results);
                        })
                        .catch(error => {
                            console.error('TomSelect AJAX error:', error);
                            callback();
                        });
                    },

                    render: {
                        no_results: function(data, escape) {
                            return '<div class="no-results py-2 px-3 text-sm text-gray-500">Tidak ada data ditemukan</div>';
                        },
                        loading: function(data, escape) {
                            return '<div class="spinner py-2 px-3 text-sm text-gray-400">Mencari data...</div>';
                        }
                    }
                });

                // Teardown cleanup when component unmounts from DOM (e.g. inside Alpine modal)
                this.$cleanup(() => {
                    if (this.instance) {
                        this.instance.destroy();
                        this.instance = null;
                    }
                });
            }
        }));
    }
};

document.addEventListener('alpine:init', registerTomSelectAjax);
if (window.Alpine) {
    registerTomSelectAjax();
}
</script>
@endpush
@endonce

@php
    \ = \ ?? (auth()->check() ? auth()->user()->school : null);
    \ = \ ?? 'Official Record';
    \ = \ ?? 'Institutional Document';
    \ = \ ?? null;
    \ = \ ?? now()->format('M d, Y h:i A');
@endphp

<table class="header-table" cellpadding="0" cellspacing="0" width="100%" style="border-bottom: 1.5pt solid #0f172a; padding-bottom: 8px; margin-bottom: 12px;">
    <tr>
        <td style="width: 65%; vertical-align: middle;">
            <div style="font-size: 15px; font-weight: bold; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ \->name ?? 'EduCore International School' }}
            </div>
            <div style="font-size: 9px; color: #64748b; margin-top: 2px; line-height: 1.3;">
                {{ \->address ?? 'Institutional Academic Campus' }}
                @if(\->phone)
                    • Phone: {{ \->phone }}
                @endif
                @if(\->email)
                    • Email: {{ \->email }}
                @endif
            </div>
            @if(\->eiin_number || \->code)
                <div style="font-size: 8px; color: #94a3b8; margin-top: 1px; font-family: 'Courier New', Courier, monospace;">
                    EIIN/REG: {{ \->eiin_number ?? \->code }}
                </div>
            @endif
        </td>
        <td style="width: 35%; text-align: right; vertical-align: middle;">
            <div>
                <span class="doc-badge">{{ \ }}</span>
            </div>
            <div style="font-size: 13px; font-weight: bold; margin-top: 4px; color: #0f172a;">
                {{ \ }}
            </div>
            @if(\)
                <div style="font-size: 10px; font-weight: bold; color: #334155; font-family: 'Courier New', Courier, monospace; margin-top: 2px;">
                    {{ \ }}
                </div>
            @endif
            <div style="font-size: 8px; color: #94a3b8; margin-top: 3px;">
                Issued: {{ \ }}
            </div>
        </td>
    </tr>
</table>

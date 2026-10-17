@php
    \ = \ ?? 3;
    \ = \ ?? (\ == 2 ? 'Authorized Cashier / Officer' : 'Prepared By');
    \ = \ ?? (\ == 2 ? 'Student / Guardian Signature' : 'Accountant / Cashier');
    \ = \ ?? 'Head of Institution / Principal';
    \ = \ ?? true;
@endphp

<div class="doc-footer" style="margin-top: 28px; page-break-inside: avoid;">
    @if(\ == 2)
        <table cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
            <tr>
                <td style="width: 40%; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1pt solid #475569; width: 80%; margin: 0 auto; padding-top: 4px;">
                        <span style="font-size: 8.5px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">{{ \ }}</span>
                    </div>
                </td>
                <td style="width: 20%;">&nbsp;</td>
                <td style="width: 40%; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1pt solid #475569; width: 80%; margin: 0 auto; padding-top: 4px;">
                        <span style="font-size: 8.5px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">{{ \ }}</span>
                    </div>
                </td>
            </tr>
        </table>
    @else
        <table cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
            <tr>
                <td style="width: 30%; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1pt solid #475569; width: 85%; margin: 0 auto; padding-top: 4px;">
                        <span style="font-size: 8.5px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">{{ \ }}</span>
                    </div>
                </td>
                <td style="width: 5%;">&nbsp;</td>
                <td style="width: 30%; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1pt solid #475569; width: 85%; margin: 0 auto; padding-top: 4px;">
                        <span style="font-size: 8.5px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">{{ \ }}</span>
                    </div>
                </td>
                <td style="width: 5%;">&nbsp;</td>
                <td style="width: 30%; text-align: center; vertical-align: bottom;">
                    <div style="border-top: 1pt solid #475569; width: 85%; margin: 0 auto; padding-top: 4px;">
                        <span style="font-size: 8.5px; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 0.3px;">{{ \ }}</span>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    @if(\)
        <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: 16px; border-top: 0.75pt solid #e2e8f0; padding-top: 4px;">
            <tr>
                <td style="width: 70%; font-size: 7.5px; color: #94a3b8;">
                    Confidential &bull; Official system-generated institutional record &bull; Verified for administrative and audit compliance.
                </td>
                <td style="width: 30%; text-align: right; font-size: 7.5px; color: #94a3b8; font-family: 'Courier New', Courier, monospace;">
                    {{ config('app.name', 'EduCore') }} ERP
                </td>
            </tr>
        </table>
    @endif
</div>

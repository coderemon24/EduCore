<footer class="border-t border-slate-200/80 bg-white py-4 px-4 sm:px-6">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
        <div class="flex items-center gap-2">
            <span>&copy; {{ date('Y') }} <strong>EduCore SIS</strong> - Laravel School Management System.</span>
            <span class="hidden md:inline text-slate-300">|</span>
            <span class="hidden md:inline">All rights reserved.</span>
        </div>
        <div class="flex items-center gap-4 text-[11px]">
            <div class="flex items-center gap-1.5 font-medium text-emerald-600">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>System Operational</span>
            </div>
            <span class="text-slate-300">•</span>
            <span>Version 2.4.0-L12</span>
            <span class="text-slate-300">•</span>
            <a href="javascript:void(0)"
               onclick="window.Swal && Swal.fire({title: 'IT Helpdesk', text: 'School IT Support: support@educore-school.edu | Ext: 4022', icon: 'question', confirmButtonColor: '#7c3aed'});"
               class="font-medium text-violet-600 hover:text-violet-700 transition">Support & Docs</a>
        </div>
    </div>
</footer>

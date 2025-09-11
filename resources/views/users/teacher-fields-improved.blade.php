<!-- Improved Teacher Fields Section -->
<div id="teacherFields" class="hidden space-y-6 border-t border-gray-200 pt-6">
    <h3 class="text-lg font-medium text-gray-900">Asignaciones Académicas</h3>
    
    @if(isset($subjects) && $subjects->count() > 0)
        @php
            // Group subjects by grade level
            $subjectsByGrade = $subjects->groupBy(function($subject) {
                return $subject->academicPlan->gradeLevel->grade_name ?? 'Sin Grado';
            });
        @endphp
        
        <div class="space-y-6">
            @foreach($subjectsByGrade as $gradeName => $gradeSubjects)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-3">{{ $gradeName }}</h4>
                    
                    @foreach($gradeSubjects as $subject)
                        <div class="bg-white p-3 mb-3 rounded border">
                            <div class="flex items-center justify-between mb-2">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           name="subject_assignments[{{ $subject->id }}][enabled]" 
                                           value="1"
                                           class="rounded border-gray-300 text-blue-600 subject-checkbox"
                                           data-subject-id="{{ $subject->id }}"
                                           {{ in_array($subject->id, old('subjects', [])) ? 'checked' : '' }}>
                                    <span class="ml-2 font-medium text-gray-900">{{ $subject->name }}</span>
                                </label>
                                <span class="text-sm text-gray-500">{{ $subject->area }}</span>
                            </div>
                            
                            <!-- Groups selection for this subject -->
                            <div class="ml-6 groups-selection hidden" data-subject-id="{{ $subject->id }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Grupos:</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                    @if(isset($groups))
                                        @foreach($groups as $group)
                                            <label class="flex items-center text-sm">
                                                <input type="checkbox" 
                                                       name="subject_assignments[{{ $subject->id }}][groups][]" 
                                                       value="{{ $group->id }}"
                                                       class="rounded border-gray-300 text-blue-600">
                                                <span class="ml-1">{{ $gradeName }}{{ $group->group_letter }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        
        <!-- JavaScript to handle subject/group interaction -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
            
            subjectCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const subjectId = this.getAttribute('data-subject-id');
                    const groupsDiv = document.querySelector('.groups-selection[data-subject-id="' + subjectId + '"]');
                    
                    if (this.checked) {
                        groupsDiv.classList.remove('hidden');
                        // Auto-select all groups by default
                        const groupCheckboxes = groupsDiv.querySelectorAll('input[type="checkbox"]');
                        groupCheckboxes.forEach(cb => cb.checked = true);
                    } else {
                        groupsDiv.classList.add('hidden');
                        // Uncheck all groups
                        const groupCheckboxes = groupsDiv.querySelectorAll('input[type="checkbox"]');
                        groupCheckboxes.forEach(cb => cb.checked = false);
                    }
                });
            });
        });
        </script>
        
    @else
        <div class="text-center py-8 text-gray-500">
            <p>No hay materias disponibles.</p>
            <p class="text-sm">Primero debe crear planes académicos y materias.</p>
        </div>
    @endif
</div>

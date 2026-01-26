  function toggleStudentType() {
        const isUpb = document.querySelector('input[name="is_student_upb"]:checked').value === '1';
        const divUpb = document.getElementById('div_facultate_upb');
        const divExt = document.getElementById('div_univ_externa');
        
        const inputUpb = document.getElementById('input_facultate_upb');
        const inputExt = document.getElementById('input_univ_externa');

        if (isUpb) {
            divUpb.classList.remove('hidden');
            divExt.classList.add('hidden');
            inputUpb.setAttribute('required', 'required'); 
            inputExt.removeAttribute('required');        
            inputExt.value = ''; 
        } else {
            divUpb.classList.add('hidden');
            divExt.classList.remove('hidden');
            inputUpb.removeAttribute('required');        
            inputExt.setAttribute('required', 'required'); 
            inputUpb.value = '';
        }
    }

    function toggleEuroavia() {
        const hasParticipated = document.querySelector('input[name="a_mai_participat"]:checked').value === '1';
        const divEv = document.getElementById('div_evenimente');
        const inputEv = document.getElementById('input_evenimente');

        if (hasParticipated) {
            divEv.classList.remove('hidden');
            inputEv.setAttribute('required', 'required');
        } else {
            divEv.classList.add('hidden');
            inputEv.removeAttribute('required'); 
            inputEv.value = '';
        }
    }
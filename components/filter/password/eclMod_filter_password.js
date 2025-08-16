
class eclMod_filter_password extends eclMod {
    control;
    formulary;

    password = '';
    repeatPassword = '';
    showNotIdenticalMessage = false;
    showPassword = false;
    score = 0;

    connectedCallback() {
        this.api('control');
        this.api('formulary');
        this.track('showNotIdenticalMessage');
        this.track('showPassword');
        this.track('score');
    }

    updatePassword(event) {
        this.password = event.detail.value;

        this.score = this.scoreCalc(this.password);

        if (!this.formulary)
            return;

        this.formulary.setField('password', this.password);
    }

    updateRepeatPassword(event) {
        this.repeatPassword = event.detail.value;
            this.showNotIdenticalMessage = (this.password != this.repeatPassword);
    }

    updateShowPassword(event) {
        this.showPassword = event.detail.value;
    }

    get _type_() {
        return this.showPassword ? 'text' : 'password';
    }

    scoreCalc(password) {
        var categories = '';
        var bonus = [0, 0, 0, 0];
        for (let i = 0; i < password.length; i++) {
            const char = password.charAt(i);
            if ('abcdefghijklmnopqrstuvwxyz'.indexOf(char) > -1) {
                categories += 'a';
                bonus[0] = 8;
            } else if ('ABCDEFGHIJKLMNOPQRSTUVWXYZ'.indexOf(char) > -1) {
                categories += 'b';
                bonus[1] = 10;
            } else if ('0123456789'.indexOf(char) > -1) {
                categories += 'c';
                bonus[2] = 8;
            } else {
                categories += 'd';
                bonus[3] = 10;
            }
        }

        var score = bonus[0] + bonus[1] + bonus[2] + bonus[3];
        var current = '';
        var last = 'f';
        var old = 'f';
        for (let i = 0; i < categories.length; i++) {
            current = categories.charAt(i);
            if (current === last && current === old)
                score += 3;
            else if (current === last)
                score += 5;
            else
                score += 8;

            old = last;
            last = current;
        }

        if (score > 100)
            return 100;
        else
            return score;
    }

    get _passwordForce_() {
        if (this.score === 100)
            return 'muito forte';
        if (this.score >= 90)
            return 'forte';
        if (this.score >= 60)
            return 'média';
        else
            return 'fraca';
    }

}

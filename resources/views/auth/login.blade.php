<x-guest-layout>
    <main class="relative z-10 flex flex-1 items-center justify-center px-4 py-8" role="main">

        <div class="glass-card w-full max-w-md animate-scaleIn rounded-3xl p-8 sm:p-10">

            <!-- ── Branding header ── -->
            <div class="d1 mb-8 animate-fadeUp text-center">
                <div aria-hidden="true" class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl"
                    style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);box-shadow:0 8px 28px rgba(37,99,235,0.40);">
                    <svg fill="none" height="32" viewBox="0 0 32 32" width="32"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11" cy="11" fill="rgba(255,255,255,0.95)" r="4" />
                        <circle cx="21" cy="11" fill="rgba(255,255,255,0.75)" r="3" />
                        <path d="M4 23c0-3.314 3.134-6 7-6s7 2.686 7 6" fill="none" stroke-linecap="round"
                            stroke-width="1.8" stroke="rgba(255,255,255,0.95)" />
                        <path d="M21 19c1.933.5 4 1.8 4 4" fill="none" stroke-linecap="round" stroke-width="1.6"
                            stroke="rgba(255,255,255,0.65)" />
                    </svg>
                </div>

                {{-- <h1 class="text-2xl font-extrabold mb-1" style="font-family:'Outfit',sans-serif;color:var(--text);">Employee Management</h1> --}}
                {{-- <p class="text-sm" style="color:var(--muted);">Sign in to your workspace portal</p> --}}

                <form action="{{ route('login') }}" class="space-y-5" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="d2 animate-fadeUp">
                        <label class="field-label" for="emailInput">Email Address</label>
                        <div class="field-wrap relative">
                            <span aria-hidden="true" class="field-icon">
                                <svg fill="currentColor" height="17" viewBox="0 0 24 24" width="17">
                                    <path
                                        d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                            </span>
                            <input aria-describedby="emailError" aria-label="Email address" autocomplete="email"
                                class="field-input" id="email" name="email" placeholder="you@company.com" required
                                type="email" value="{{ old('email') ?? '' }}" />
                        </div>
                        <p class="err-msg" id="emailError" role="alert">
                            <svg fill="currentColor" height="13" viewBox="0 0 24 24" width="13">
                                <path
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                            Please enter a valid email address.
                        </p>
                    </div>

                    <div class="d3 animate-fadeUp">
                        <label class="field-label" for="pwInput">Password</label>
                        <div class="field-wrap relative">
                            <span aria-hidden="true" class="field-icon">
                                <svg fill="currentColor" height="17" viewBox="0 0 24 24" width="17">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                            </span>
                            <input aria-describedby="pwError" aria-label="Password" autocomplete="current-password"
                                class="field-input" id="pwInput" name="password" placeholder="Enter your password"
                                required type="password" />

                            <button aria-label="Toggle password visibility" class="eye-toggle" id="eyeBtn"
                                onclick="togglePassword()" type="button"></button>
                        </div>
                    </div>
                    @if ($errors)
                        @foreach ($errors as $error)
                            <p class="err-msg" id="pwError" role="alert">
                                <svg fill="currentColor" height="13" viewBox="0 0 24 24" width="13">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                                </svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    @endif

                    <div class="d4 flex animate-fadeUp items-center justify-between">
                        <label class="cb-custom flex cursor-pointer select-none items-center gap-2.5">
                            <input aria-label="Remember me" id="remember" name="remember" type="checkbox" />
                            <div class="cb-box">
                                <svg aria-hidden="true" class="cb-tick" fill="none" height="11"
                                    viewBox="0 0 12 12" width="11">
                                    <path d="M2 6l3 3 5-5" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" stroke="white" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium" style="color:var(--muted);">Remember me</span>
                        </label>
                    </div>

                    <div class="d5 animate-fadeUp pt-1">
                        <button class="btn-primary" type="submit">
                            <span class="relative z-10 flex items-center justify-center gap-2" id="btnContent">
                                <svg aria-hidden="true" fill="currentColor" height="17" viewBox="0 0 24 24"
                                    width="17">
                                    <path
                                        d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z" />
                                </svg>
                                Sign In to Portal
                            </span>
                        </button>
                    </div>
                </form>

                <div class="d8 mt-7 animate-fadeUp pt-5" style="border-top:1px solid var(--border);">
                    <p class="text-center text-xs" style="color:var(--muted);">
                        Need access?
                        <a class="font-medium text-blue-500 hover:underline" href="#"
                            onclick="return false;">Contact IT Support</a>
                        &nbsp;·&nbsp;
                        <a class="font-medium text-blue-500 hover:underline" href="#"
                            onclick="return false;">Privacy Policy</a>
                    </p>
                    <p class="mt-2 text-center text-xs" style="color:var(--muted);opacity:0.6;">
                        © 2026 PeopleCore&nbsp; · &nbsp;v5.2.0&nbsp; · &nbsp;Enterprise Edition
                    </p>
                </div>
            </div>
    </main>
</x-guest-layout>

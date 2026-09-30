# SECURITY HARDENING PLAN: E-OFFICE RIDOGALIH
**Role:** Senior Laravel Security Engineer & Full-Stack Security Architect  
**Framework:** Laravel 13 | PHP 8.4 | MySQL | Blade | Vite | Tailwind  

---

## 1. Architectural Principles & Critical Rules

### 1.1 Strict Separation: MENU != PERMISSION
Menu is purely presentation/navigation metadata. Under no circumstance should a menu item or URL route name act as the authorization security boundary.

- **Authorization Pipeline (Server-side Enforcement):**
  $$\text{User} \longrightarrow \text{Role} \longrightarrow \text{Permission} \longrightarrow \text{Gate / Policy} \longrightarrow \text{Protected Action / Resource}$$
- **Navigation Pipeline (Presentation Only):**
  $$\text{Permission} \longrightarrow \text{Menu} \longrightarrow \text{MenuService} \longrightarrow \text{Blade}$$

Blade templates will never make security decisions, never execute ad-hoc database queries for navigation authorization, and never use role-string checks for access control.

---

## 2. Identified Vulnerabilities & Remediation Strategy

| # | Current Finding | Risk Level | Remediation Plan |
|---|----------------|------------|------------------|
| 1 | Hardcoded Super Admin credentials in `OrganizationSeeder.php` | CRITICAL | Require private `SUPERADMIN_EMAIL`, `SUPERADMIN_PASSWORD`, and `SUPERADMIN_NIK` values; fail seeding if missing or malformed. |
| 2 | `AuditLogObserver` leaks passwords/tokens/attributes | CRITICAL | Implement `AuditDataSanitizer` with strict blacklists and masking. Never dump raw `$model->getAttributes()`. |
| 3 | Route-based authorization via `menus.url_route` in `CheckUserPrivilege` and `AppServiceProvider` | CRITICAL | Replace with standard Laravel RBAC (`permissions`, `role_permissions`), Gates and Policies. |
| 4 | Privilege Escalation in Role/Menu management | HIGH | Protect system permissions (`system.admin`, `security.*`) from unauthorized assignment by non-superadmins. |
| 5 | Super Admin check via string comparison (`$role->name === 'Super Admin'`) | HIGH | Use system permission (`system.admin`) or explicit system flag; not fragile role string. |
| 6 | Menu and authorization logic hardcoded in Blade | MEDIUM | Inject pre-authorized navigation tree via `MenuService`. Remove all inline DB queries in Blade. |
| 7 | Sensitive documents (surat masuk, QR, foto) stored in `public` disk | HIGH | Move to `private` disk with Policy-governed downloads and temporary signed URLs. |
| 8 | Insecure OTP handling (stored plaintext in session, no attempt limits) | HIGH | Store hashed OTP with expiration, max attempt counter, and throttle verify endpoint. |
| 9 | Public registration NIK enumeration | MEDIUM | Normalize registration error responses to prevent database probing. |
| 10 | Internal exception messages exposed to users (`$e->getMessage()`) | HIGH | Log internal exceptions via `report($e)` and return generic user-friendly messages. |
| 11 | Insecure session and environment settings | HIGH | Enforce `SESSION_ENCRYPT=true`, secure cookie flags, and production config baselines. |
| 12 | Missing HTTP Security Headers | HIGH | Add global `SecurityHeadersMiddleware` (X-Content-Type-Options, X-Frame-Options, HSTS, Referrer-Policy, Permissions-Policy). |
| 13 | Unconfigured Content Security Policy (CSP) | MEDIUM | Implement CSP header with proper directives for Vite, fonts, and assets. |
| 14 | Database backup credential exposure via `env()` and CLI | MEDIUM | Read credentials safely via `config()`, avoid plain CLI flag leakage. |
| 15 | Document checksum vs digital signature ambiguity | LOW | Differentiate HMAC/SHA-256 integrity checksums from cryptographic digital signatures. |

---

## 3. Work Phases Breakdown

```
Phase 0: Emergency Security Cleanup
  ├── Default credentials & secrets hardening
  ├── Audit log redaction policy (AuditDataSanitizer)
  ├── Sensitive exception masking
  └── Session & debug config hardening

Phase 1: Foundation (Enterprise RBAC & Gate/Policy)
  ├── ULID / public_id consistency (preserve integer FKs)
  ├── Schema: permissions & role_permissions
  ├── Menu schema update (link to permission_id)
  ├── Authorization Engine (Gate & Policies for User, Role, Penduduk, SuratMasuk, SuratKeluar, Menu, AuditLog)
  └── Role privilege escalation guard (protected permissions)

Phase 2: Data Validation & Resource Security
  ├── Named rate limits (Login, registration, OTP, API, file and sensitive endpoints)
  ├── OTP hardening (hashed, expiry, max attempts)
  ├── Private file storage & Policy-based download streams
  └── Encrypted non-queried PII with legacy-data migration

Phase 3: Infrastructure Hardening
  ├── Global Security Headers Middleware
  ├── Content Security Policy rollout compatible with current app assets
  ├── Configurable IP restriction on security-sensitive administrator routes
  └── Mandatory TOTP MFA and one-time recovery codes for System Admin

Phase 4: Security Verification & Regression Testing
  ├── Comprehensive Feature Tests (Auth, RBAC, IDOR, OTP, Files, Headers)
  ├── Dependency security review
  └── Route & middleware audit

Phase 5: Frontend Clean-up
  ├── MenuService providing pre-authorized navigation items
  └── Refactor Blade layouts to consume sanitized navigation metadata without security logic
```

## 4. Phase 0 Progress Notes

- Registration lookup failures (unknown NIK, an existing account, or an already-used email) share one generic response to reduce account/NIK enumeration.
- Registration OTPs are stored as password hashes in the server-side session, expire after 10 minutes, and are invalidated after five failed attempts. The guest authentication routes are also throttled.
- Session encryption is enabled by default. HTTP-only and SameSite=Lax cookie settings are shown in `.env.example`; secure cookies default on when `APP_ENV=production` and can be explicitly configured with `SESSION_SECURE_COOKIE`.
- Mail transport exceptions are reported to the application logger and are not exposed in the registration response.

Set `APP_KEY` before enabling encrypted sessions. For production deployments, serve the application exclusively over HTTPS and verify the effective `SESSION_SECURE_COOKIE=true` setting. Do not copy the local `APP_DEBUG=true` example value into production.

`UserController` was checked for controller exception messages exposed to users; no `$e->getMessage()` usage was found there.

## 5. Phase 1 Foundation Progress

- Added `permissions` and `role_permissions`, plus nullable `menus.permission_id` and `menus.is_active`. Permission keys are unique; `role_menus` remains only as legacy navigation metadata during migration.
- Added the requested domain permission keys and permissions for existing dashboard, letter-type, and report endpoints. `system.admin` is now the Super Admin bypass primitive; role labels are not used for authorization.
- `PermissionSeeder` maps exact legacy menu grants to permission keys and maps known module wildcards to ordinary module permissions. Legacy grants do not create protected security grants; those require a System Admin to assign explicitly.
- `CheckUserPrivilege` authorizes by controller action-to-permission mapping and denies unrecognized protected actions. Controllers and Form Requests enforce permission keys and resource policies independently.
- Added policies for User, Role, Permission, Penduduk, SuratMasuk, SuratKeluar, AuditLog, and Menu. Role permission assignment requires both `roles.update` and `security.permissions.assign`, and protected permissions are rejected for non-System Admins.
- Sidebar and header search now consume the pre-authorized `MenuService` result. Menu route metadata accepts only registered static route names; routes requiring URL parameters are not offered as navigation links.
- The unsupported `surat-keluar.edit` resource route was removed because its controller has no `edit` action.

Deployment order: configure `SUPERADMIN_NIK`, `SUPERADMIN_EMAIL`, and `SUPERADMIN_PASSWORD` (minimum 12 characters) as private environment values, run migrations, then run `php artisan db:seed --force` so the permission registry and legacy grants are populated before enabling protected routes. The seeder now fails closed if bootstrap credentials are missing or malformed; it no longer falls back to a shared/default password. Existing role-menu wildcard grants for the known domain modules are expanded to those module permissions; unrecognized wildcard/admin grants are not carried forward. Existing `role_menus` rows are retained and no longer authorize backend actions.

The bootstrap seeder also used values outside the resident enums for education and dusun. Those were corrected to valid enum backing values so a clean seeded install can reach the new permission seeders.

Phase 1 regression coverage currently verifies role permission allow/deny, permission-based Super Admin status, permission middleware, protected-permission escalation prevention, legacy menu grants no longer authorizing requests, legacy grant backfill, and permission-filtered navigation.

## 6. Phase 2 Data & Resource Security

- Added named cache-backed limits for login (NIK and IP), registration, OTP (session/email/IP), device API, file downloads, and sensitive mutations/exports. Counters use Laravel's configured cache store; use a shared Redis/database cache store when the application runs on multiple web nodes.
- Registered `routes/api.php` with Laravel's router so the existing device-verification endpoint is active and protected by the named API limiter.
- Added a non-served `private` disk. Resident photos, incoming-letter scans, outgoing-letter QR codes, and queued resident Excel/CSV exports are now stored there. The existing public village logo remains public.
- Private resident photos and incoming-letter scans are served only through authenticated routes with permission middleware, resource Gate/Policy checks, safe storage-prefix validation, and `private, no-store` response headers. Export files are owned by the requesting user, exposed through a policy-protected download route, and expire after three days. Export cleanup removes expired private files and tracking rows.
- Added `security:migrate-sensitive-files` to move legacy files under the known sensitive public directories into private storage without overwriting collisions. Run this during the deployment maintenance window before reopening traffic; verify the reported moved count, then check that the corresponding paths no longer exist under `storage/app/public`. A collision aborts the command safely for manual investigation. The command deliberately leaves `logo/` public.
- `Penduduk.nama_ibu` already uses Laravel's encrypted cast. A data migration upgrades its column from `VARCHAR` to `TEXT` (Laravel ciphertext expands beyond 255 characters) and encrypts legacy plaintext values. Apply migrations with the correct `APP_KEY`; do not rotate the key without a separate data re-encryption plan. SQL search/filtering by this encrypted field is intentionally unsupported.
- Photo and scan routes intentionally do not issue signed URLs: authorization is checked on each request. The local disk's temporary-serving feature has been disabled because it shares the private storage root.

Phase 2 regression coverage checks login throttling, authorized private photo retrieval, safe legacy-file migration (including leaving logos public), encrypted-name reads with ciphertext larger than the old column, and owner-only access to queued exports.

## 7. Phase 3 Infrastructure Hardening

- Added global `SecurityHeadersMiddleware`: `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`, and `Cross-Origin-Opener-Policy`. HSTS is emitted only for secure production requests, for one year; `includeSubDomains` is opt-in because it can affect unrelated subdomains.
- Added a CSP for same-origin resources and the currently used Alpine/ApexCharts, Tailwind validation page, Google Fonts, Font Awesome, and validation-page texture. `SECURITY_CSP_REPORT_ONLY=true` is the initial default: violations can be inspected in browsers without breaking inline Blade handlers. Production CSP deliberately excludes `unsafe-eval`; `unsafe-inline` remains temporarily for existing inline scripts/styles and Alpine expressions. Do not enforce a strict CSP until remaining inline resources and Alpine's CSP compatibility are addressed. Development-only Vite origins are added outside production.
- Sensitive role/permission management, village settings, audit logs, general system settings/backups, and MFA enrollment/recovery pages pass through `admin.ip`. Enable it only after setting `SECURITY_ADMIN_ALLOWED_IPS` to comma-separated trusted IPv4/IPv6 addresses or CIDR ranges. If enabled with an empty allowlist, requests fail with HTTP 503; unmatched clients receive 403. The default is disabled so an unconfigured deployment is not unexpectedly locked out. Configure Laravel trusted proxies before using forwarded client IPs behind a reverse proxy.
- Added encrypted TOTP secrets for System Admin accounts. Their first login after this deployment requires enrollment; enrollment confirmation validates an RFC 6238 six-digit TOTP (30-second period, one-step clock window), and all other authenticated privileged routes stay gated until setup is complete. Subsequent logins require TOTP before creating an authenticated session. Used time steps are claimed atomically to reject code replay; MFA challenges expire after five minutes and allow at most five attempts.
- Enrollment produces eight random one-time recovery codes. Only password hashes of those codes are stored, inside an encrypted model attribute; the cleartext codes are shown once and must be kept offline. A recovery code is atomically consumed on use. Apply the MFA migration with the current production `APP_KEY`, ensure each System Admin can use an authenticator before rollout, and save the displayed recovery codes. If all recovery methods are lost, follow the organization's verified operator recovery procedure; do not reset MFA through an unaudited public endpoint.
- `.env.example` now documents CSP rollout, the IP allowlist, and HSTS subdomain controls. Security Phase 3 tests cover headers/CSP modes, IP/CIDR allow/deny and missing allowlist behavior, RFC TOTP vectors, forced admin enrollment, authenticated TOTP login, replay rejection, recovery-code one-time use, attempt limits, and audit redaction.

## 8. Phase 4 Security Verification & Regression Testing

- Added route-audit tests asserting that every route with `privilege` also requires authentication and MFA enrollment, and that role/menu administration, village settings, audit logs, system settings, and MFA routes additionally require `admin.ip`.
- Added an IDOR regression test for resident photos: a user can retrieve the photo linked to their own account but cannot retrieve another resident's photo without a broad view permission. Existing suites also exercise RBAC/resource policies, registration OTP expiry and attempts, private file/export access, rate limits, security headers, and MFA.
- Reviewed locked Composer and npm dependencies. Updated only `laravel/framework` (13.25.0 → 13.34.0) and `league/flysystem` (3.35.2 → 3.36.0) to address the advisories reported for Laravel XSS in debug-page information and malformed UTF-8 path normalization in Flysystem. Keep `APP_DEBUG=false` in production regardless of the framework patch.
- Composer and npm audits must report no known advisories after the updates. Regression tests, route/middleware checks, and clean migration/seeding are run as part of this phase; no throughput or SLO claims are inferred from functional tests.

## 9. Phase 5 Frontend Cleanup

- The application layout now builds the permitted sidebar tree and global search list once per render from `MenuService`'s permission-filtered result. Blade partials consume those arrays; the sidebar no longer resolves route names, inspects the request route, or infers which menu routes are active.
- Navigation metadata contains only registered routes without required parameters, escaped labels, validated Font Awesome class names (with a safe fallback), and precomputed active state. Search results derive from that same authorized tree, so navigation and search cannot expose different menu sets.
- Moved the sidebar scrollbar rules into the Vite-managed application stylesheet. Added regression coverage for permission filtering, static-route-only URLs, active state, search/sidebar parity, and actual Blade rendering. Vite production build and the complete test suite pass.
- Remaining security follow-up: this phase does not remove `unsafe-inline` from CSP. The app still has inline scripts/styles and Alpine expressions in multiple views; assess an Alpine CSP-compatible build and migrate inline handlers/scripts before switching `SECURITY_CSP_REPORT_ONLY=false`.

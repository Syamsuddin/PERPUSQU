# 38_TREE.md

## 1. Nama Dokumen

TREE Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint struktur file dan folder implementasi resmi

### 2.3 Status Dokumen

Resmi, acuan wajib struktur file dan folder aplikasi PERPUSQU

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan struktur file dan folder resmi implementasi PERPUSQU berbasis Laravel monolith modular agar seluruh coding backend, frontend, queue, search, OCR, reporting, import export, audit, testing, dan deployment berjalan konsisten dengan seluruh blueprint yang telah ditulis sebelumnya.

## 3. Dokumen Acuan Wajib

01_EXECUTIVE_SUMMARY.md
02_STACK_TEKNOLOGI.md
03_ARSITEKTUR_MODULAR.md
04_PRD.md
05_SRS.md
06_USE_CASE.md
07_ROLE_PERMISSION_MATRIX.md
08_MENU_MAP.md
09_ROUTE_MAP.md
10_VIEW_MAP.md
11_CONTROLLER_MAP.md
12_SERVICE_LAYER.md
13_MODEL_MAP.md
14_SCHEMA.sql
15_SEED.sql
16_VALIDATION_RULES.md
17_WORKFLOW_STATE_MACHINE.md
18_UI_UX_STANDARD.md
19_OPAC_UX_FLOW.md
20_API_CONTRACT.md
21_SEARCH_INDEXING_SPEC.md
22_STORAGE_FILE_POLICY.md
23_OCR_AND_DIGITAL_PROCESSING.md
24_NOTIFICATION_RULES.md
25_REPORTING_SPEC.md
26_IMPORT_EXPORT_SPEC.md
27_INTEGRATION_SPEC.md
28_SECURITY_POLICY.md
29_AUDIT_LOG_SPEC.md
30_ERROR_CODE.md
31_TEST_PLAN.md
32_TEST_SCENARIO.md
33_DEPLOYMENT_GUIDE.md
34_ENV_CONFIGURATION.md
35_BACKUP_AND_RECOVERY.md
36_PERFORMANCE_GUIDE.md
37_CODING_STANDARD.md

## 4. Struktur File dan Folder Resmi

```text
PERPUSQU
├── app
│   ├── Console
│   │   ├── Commands
│   │   │   ├── CleanupExportTempFilesCommand.php
│   │   │   ├── CleanupObsoleteAssetsCommand.php
│   │   │   ├── CleanupOcrTempFilesCommand.php
│   │   │   ├── CleanupOldReleasesCommand.php
│   │   │   ├── SearchReindexPublicRecordsCommand.php
│   │   │   ├── SearchSyncSettingsCommand.php
│   │   │   ├── VerifyBackupHealthCommand.php
│   │   │   └── VerifySystemReadinessCommand.php
│   │   └── Kernel.php
│   ├── Exceptions
│   │   ├── Handler.php
│   │   ├── DomainException.php
│   │   ├── AuthenticationException.php
│   │   ├── AuthorizationException.php
│   │   ├── BusinessRuleException.php
│   │   ├── FileStorageException.php
│   │   ├── ImportException.php
│   │   ├── OcrException.php
│   │   ├── ReindexException.php
│   │   ├── ReportExportException.php
│   │   └── SearchException.php
│   ├── Http
│   │   ├── Controllers
│   │   │   └── Controller.php
│   │   ├── Middleware
│   │   │   ├── Authenticate.php
│   │   │   ├── ForceHttps.php
│   │   │   ├── RedirectIfAuthenticated.php
│   │   │   ├── SecurityHeaders.php
│   │   │   ├── TrustProxies.php
│   │   │   └── VerifyCsrfToken.php
│   │   └── Kernel.php
│   ├── Models
│   │   └── User.php
│   ├── Modules
│   │   ├── Audit
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   └── AuditLogController.php
│   │   │   │   └── Requests
│   │   │   │       └── AuditLogIndexRequest.php
│   │   │   ├── Models
│   │   │   │   ├── ActivityLog.php
│   │   │   │   ├── QueueMonitorSnapshot.php
│   │   │   │   └── ReportExportHistory.php
│   │   │   ├── Policies
│   │   │   │   └── ActivityLogPolicy.php
│   │   │   ├── Queries
│   │   │   │   └── AuditLogQuery.php
│   │   │   ├── Services
│   │   │   │   ├── AuditLogService.php
│   │   │   │   └── QueueMonitorService.php
│   │   │   └── Support
│   │   │       ├── AuditEventFactory.php
│   │   │       ├── AuditValueSanitizer.php
│   │   │       └── QueueHealthResolver.php
│   │   ├── Catalog
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── BibliographicRecordController.php
│   │   │   │   │   ├── CatalogAuthorController.php
│   │   │   │   │   └── CatalogSubjectController.php
│   │   │   │   └── Requests
│   │   │   │       ├── BibliographicRecordFilterRequest.php
│   │   │   │       ├── PublishBibliographicRecordRequest.php
│   │   │   │       ├── StoreBibliographicRecordRequest.php
│   │   │   │       ├── UnpublishBibliographicRecordRequest.php
│   │   │   │       └── UpdateBibliographicRecordRequest.php
│   │   │   ├── Models
│   │   │   │   ├── BibliographicRecord.php
│   │   │   │   ├── BibliographicRecordAuthor.php
│   │   │   │   └── BibliographicRecordSubject.php
│   │   │   ├── Policies
│   │   │   │   └── BibliographicRecordPolicy.php
│   │   │   ├── Queries
│   │   │   │   ├── BibliographicRecordIndexQuery.php
│   │   │   │   └── PublicBibliographicRecordQuery.php
│   │   │   ├── Services
│   │   │   │   ├── BibliographicRecordPublishService.php
│   │   │   │   ├── BibliographicRecordService.php
│   │   │   │   ├── CatalogAuthorRelationService.php
│   │   │   │   └── CatalogSubjectRelationService.php
│   │   │   └── Support
│   │   │       ├── BibliographicRecordHydrator.php
│   │   │       └── BibliographicRecordStateGuard.php
│   │   ├── Circulation
│   │   │   ├── DTOs
│   │   │   │   ├── LoanEligibilityResult.php
│   │   │   │   ├── LoanTransactionResult.php
│   │   │   │   └── ReturnProcessingResult.php
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── FineController.php
│   │   │   │   │   ├── LoanController.php
│   │   │   │   │   ├── LoanRenewalController.php
│   │   │   │   │   └── ReturnTransactionController.php
│   │   │   │   └── Requests
│   │   │   │       ├── FineFilterRequest.php
│   │   │   │       ├── LoanFilterRequest.php
│   │   │   │       ├── RenewLoanRequest.php
│   │   │   │       ├── ReturnLoanRequest.php
│   │   │   │       └── StoreLoanRequest.php
│   │   │   ├── Models
│   │   │   │   ├── Fine.php
│   │   │   │   ├── Loan.php
│   │   │   │   ├── LoanRenewal.php
│   │   │   │   └── ReturnTransaction.php
│   │   │   ├── Policies
│   │   │   │   ├── FinePolicy.php
│   │   │   │   └── LoanPolicy.php
│   │   │   ├── Queries
│   │   │   │   ├── ActiveLoanQuery.php
│   │   │   │   ├── CirculationReportQuery.php
│   │   │   │   └── OverdueLoanQuery.php
│   │   │   ├── Services
│   │   │   │   ├── FineCalculationService.php
│   │   │   │   ├── LoanEligibilityService.php
│   │   │   │   ├── LoanRenewalService.php
│   │   │   │   ├── LoanTransactionService.php
│   │   │   │   └── ReturnProcessingService.php
│   │   │   └── Support
│   │   │       ├── CirculationStateGuard.php
│   │   │       ├── DueDateCalculator.php
│   │   │       └── FineAmountCalculator.php
│   │   ├── Collection
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── ItemConditionController.php
│   │   │   │   │   ├── PhysicalItemController.php
│   │   │   │   │   └── PhysicalItemStatusHistoryController.php
│   │   │   │   └── Requests
│   │   │   │       ├── ChangePhysicalItemStatusRequest.php
│   │   │   │       ├── PhysicalItemFilterRequest.php
│   │   │   │       ├── StorePhysicalItemRequest.php
│   │   │   │       └── UpdatePhysicalItemRequest.php
│   │   │   ├── Models
│   │   │   │   ├── ItemCondition.php
│   │   │   │   ├── PhysicalItem.php
│   │   │   │   └── PhysicalItemStatusHistory.php
│   │   │   ├── Policies
│   │   │   │   └── PhysicalItemPolicy.php
│   │   │   ├── Queries
│   │   │   │   ├── ItemAvailabilityQuery.php
│   │   │   │   └── PhysicalItemIndexQuery.php
│   │   │   ├── Services
│   │   │   │   ├── PhysicalItemService.php
│   │   │   │   └── PhysicalItemStatusService.php
│   │   │   └── Support
│   │   │       ├── ItemAvailabilityFormatter.php
│   │   │       └── PhysicalItemStateGuard.php
│   │   ├── Core
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── DashboardController.php
│   │   │   │   │   ├── InstitutionProfileController.php
│   │   │   │   │   └── SystemSettingController.php
│   │   │   │   └── Requests
│   │   │   │       ├── UpdateInstitutionProfileRequest.php
│   │   │   │       └── UpdateSystemSettingRequest.php
│   │   │   ├── Models
│   │   │   │   ├── InstitutionProfile.php
│   │   │   │   └── SystemSetting.php
│   │   │   ├── Policies
│   │   │   │   ├── InstitutionProfilePolicy.php
│   │   │   │   └── SystemSettingPolicy.php
│   │   │   ├── Services
│   │   │   │   ├── DashboardService.php
│   │   │   │   ├── InstitutionProfileService.php
│   │   │   │   └── SystemSettingService.php
│   │   │   └── Support
│   │   │       ├── AppSettingResolver.php
│   │   │       └── OperationalRuleResolver.php
│   │   ├── DigitalRepository
│   │   │   ├── DTOs
│   │   │   │   ├── AssetAccessDecision.php
│   │   │   │   ├── ImportSummaryResult.php
│   │   │   │   └── OcrResult.php
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── AssetAccessController.php
│   │   │   │   │   ├── AssetPreviewController.php
│   │   │   │   │   ├── DigitalAssetAccessRuleController.php
│   │   │   │   │   ├── DigitalAssetController.php
│   │   │   │   │   └── QueueMonitorController.php
│   │   │   │   └── Requests
│   │   │   │       ├── ReindexDigitalAssetRequest.php
│   │   │   │       ├── RunDigitalAssetOcrRequest.php
│   │   │   │       ├── StoreDigitalAssetAccessRuleRequest.php
│   │   │   │       ├── StoreDigitalAssetRequest.php
│   │   │   │       ├── UpdateDigitalAssetAccessRuleRequest.php
│   │   │   │       └── UpdateDigitalAssetRequest.php
│   │   │   ├── Jobs
│   │   │   │   ├── ProcessDigitalAssetOcrJob.php
│   │   │   │   ├── ReindexBibliographicRecordJob.php
│   │   │   │   └── RetryFailedOcrJob.php
│   │   │   ├── Models
│   │   │   │   ├── DigitalAsset.php
│   │   │   │   ├── DigitalAssetAccessRule.php
│   │   │   │   └── OcrText.php
│   │   │   ├── Policies
│   │   │   │   ├── DigitalAssetAccessRulePolicy.php
│   │   │   │   └── DigitalAssetPolicy.php
│   │   │   ├── Queries
│   │   │   │   ├── DigitalAssetIndexQuery.php
│   │   │   │   └── PublicDigitalAssetQuery.php
│   │   │   ├── Services
│   │   │   │   ├── AssetStreamingService.php
│   │   │   │   ├── DigitalAssetAccessService.php
│   │   │   │   ├── DigitalAssetService.php
│   │   │   │   ├── DigitalAssetUploadService.php
│   │   │   │   ├── OcrProcessingService.php
│   │   │   │   └── QueueMonitorService.php
│   │   │   └── Support
│   │   │       ├── OcrTextNormalizer.php
│   │   │       ├── PdfRasterizer.php
│   │   │       ├── PdfTextExtractor.php
│   │   │       ├── PublicAssetVisibilityResolver.php
│   │   │       └── TempFileManager.php
│   │   ├── Identity
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── AuthController.php
│   │   │   │   │   ├── PermissionController.php
│   │   │   │   │   ├── RoleController.php
│   │   │   │   │   └── UserController.php
│   │   │   │   └── Requests
│   │   │   │       ├── LoginRequest.php
│   │   │   │       ├── ResetUserPasswordRequest.php
│   │   │   │       ├── StoreRoleRequest.php
│   │   │   │       ├── StoreUserRequest.php
│   │   │   │       ├── UpdateRolePermissionRequest.php
│   │   │   │       ├── UpdateRoleRequest.php
│   │   │   │       ├── UpdateUserProfileRequest.php
│   │   │   │       └── UpdateUserRequest.php
│   │   │   ├── Policies
│   │   │   │   ├── RolePolicy.php
│   │   │   │   └── UserPolicy.php
│   │   │   ├── Services
│   │   │   │   ├── AuthenticationService.php
│   │   │   │   ├── PermissionService.php
│   │   │   │   ├── RoleService.php
│   │   │   │   └── UserService.php
│   │   │   └── Support
│   │   │       ├── PermissionMatrixResolver.php
│   │   │       └── SessionSecurityService.php
│   │   ├── MasterData
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── AuthorController.php
│   │   │   │   │   ├── ClassificationController.php
│   │   │   │   │   ├── CollectionTypeController.php
│   │   │   │   │   ├── FacultyController.php
│   │   │   │   │   ├── LanguageController.php
│   │   │   │   │   ├── PublisherController.php
│   │   │   │   │   ├── RackLocationController.php
│   │   │   │   │   ├── StudyProgramController.php
│   │   │   │   │   └── SubjectController.php
│   │   │   │   └── Requests
│   │   │   │       ├── StoreAuthorRequest.php
│   │   │   │       ├── StoreClassificationRequest.php
│   │   │   │       ├── StoreCollectionTypeRequest.php
│   │   │   │       ├── StoreFacultyRequest.php
│   │   │   │       ├── StoreLanguageRequest.php
│   │   │   │       ├── StorePublisherRequest.php
│   │   │   │       ├── StoreRackLocationRequest.php
│   │   │   │       ├── StoreStudyProgramRequest.php
│   │   │   │       ├── StoreSubjectRequest.php
│   │   │   │       ├── UpdateAuthorRequest.php
│   │   │   │       ├── UpdateClassificationRequest.php
│   │   │   │       ├── UpdateCollectionTypeRequest.php
│   │   │   │       ├── UpdateFacultyRequest.php
│   │   │   │       ├── UpdateLanguageRequest.php
│   │   │   │       ├── UpdatePublisherRequest.php
│   │   │   │       ├── UpdateRackLocationRequest.php
│   │   │   │       ├── UpdateStudyProgramRequest.php
│   │   │   │       └── UpdateSubjectRequest.php
│   │   │   ├── Models
│   │   │   │   ├── Author.php
│   │   │   │   ├── Classification.php
│   │   │   │   ├── CollectionType.php
│   │   │   │   ├── Faculty.php
│   │   │   │   ├── Language.php
│   │   │   │   ├── Publisher.php
│   │   │   │   ├── RackLocation.php
│   │   │   │   ├── StudyProgram.php
│   │   │   │   └── Subject.php
│   │   │   ├── Policies
│   │   │   │   ├── AuthorPolicy.php
│   │   │   │   ├── ClassificationPolicy.php
│   │   │   │   ├── CollectionTypePolicy.php
│   │   │   │   ├── FacultyPolicy.php
│   │   │   │   ├── LanguagePolicy.php
│   │   │   │   ├── PublisherPolicy.php
│   │   │   │   ├── RackLocationPolicy.php
│   │   │   │   ├── StudyProgramPolicy.php
│   │   │   │   └── SubjectPolicy.php
│   │   │   ├── Services
│   │   │   │   ├── AuthorService.php
│   │   │   │   ├── ClassificationService.php
│   │   │   │   ├── CollectionTypeService.php
│   │   │   │   ├── FacultyService.php
│   │   │   │   ├── LanguageService.php
│   │   │   │   ├── PublisherService.php
│   │   │   │   ├── RackLocationService.php
│   │   │   │   ├── StudyProgramService.php
│   │   │   │   └── SubjectService.php
│   │   │   └── Support
│   │   │       ├── LookupOptionBuilder.php
│   │   │       └── MasterDataStateGuard.php
│   │   ├── Member
│   │   │   ├── DTOs
│   │   │   │   ├── ImportRowError.php
│   │   │   │   └── MemberImportSummaryResult.php
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── MemberController.php
│   │   │   │   │   └── MemberImportController.php
│   │   │   │   └── Requests
│   │   │   │       ├── MemberFilterRequest.php
│   │   │   │       ├── MemberImportRequest.php
│   │   │   │       ├── StoreMemberRequest.php
│   │   │   │       ├── UpdateMemberBlockStatusRequest.php
│   │   │   │       └── UpdateMemberRequest.php
│   │   │   ├── Models
│   │   │   │   └── Member.php
│   │   │   ├── Policies
│   │   │   │   └── MemberPolicy.php
│   │   │   ├── Services
│   │   │   │   ├── MemberBlockingService.php
│   │   │   │   ├── MemberImportService.php
│   │   │   │   └── MemberService.php
│   │   │   └── Support
│   │   │       ├── MemberEligibilityResolver.php
│   │   │       ├── MemberImportRowValidator.php
│   │   │       └── MemberImportTemplateDefinition.php
│   │   ├── Opac
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   ├── OpacHomeController.php
│   │   │   │   │   ├── OpacRecordController.php
│   │   │   │   │   ├── OpacSearchController.php
│   │   │   │   │   └── PublicAssetPreviewController.php
│   │   │   │   └── Requests
│   │   │   │       ├── OpacSearchRequest.php
│   │   │   │       └── PublicSuggestionRequest.php
│   │   │   ├── Queries
│   │   │   │   ├── OpacHydrationQuery.php
│   │   │   │   └── OpacSearchResultQuery.php
│   │   │   ├── Services
│   │   │   │   ├── OpacBrowseService.php
│   │   │   │   ├── OpacSearchService.php
│   │   │   │   └── PublicAssetPreviewService.php
│   │   │   └── Support
│   │   │       ├── OpacRecordPresenter.php
│   │   │       ├── OpacSuggestionBuilder.php
│   │   │       └── PublicVisibilityResolver.php
│   │   ├── Profile
│   │   │   ├── Http
│   │   │   │   ├── Controllers
│   │   │   │   │   └── ProfileController.php
│   │   │   │   └── Requests
│   │   │   │       ├── UpdateMyPasswordRequest.php
│   │   │   │       └── UpdateMyProfileRequest.php
│   │   │   └── Services
│   │   │       └── ProfileService.php
│   │   └── Reporting
│   │       ├── DTOs
│   │       │   └── ReportExportResult.php
│   │       ├── Http
│   │       │   ├── Controllers
│   │       │   │   ├── CirculationReportController.php
│   │       │   │   ├── CollectionReportController.php
│   │       │   │   ├── DashboardReportController.php
│   │       │   │   ├── DigitalAccessReportController.php
│   │       │   │   ├── FineReportController.php
│   │       │   │   ├── MemberReportController.php
│   │       │   │   └── PopularCollectionReportController.php
│   │       │   └── Requests
│   │       │       ├── CirculationReportExportRequest.php
│   │       │       ├── CirculationReportFilterRequest.php
│   │       │       ├── CollectionReportExportRequest.php
│   │       │       ├── CollectionReportFilterRequest.php
│   │       │       ├── DashboardReportFilterRequest.php
│   │       │       ├── DigitalAccessReportExportRequest.php
│   │       │       ├── DigitalAccessReportFilterRequest.php
│   │       │       ├── FineReportExportRequest.php
│   │       │       ├── FineReportFilterRequest.php
│   │       │       ├── MemberReportExportRequest.php
│   │       │       ├── MemberReportFilterRequest.php
│   │       │       ├── PopularCollectionReportExportRequest.php
│   │       │       └── PopularCollectionReportFilterRequest.php
│   │       ├── Jobs
│   │       │   ├── BuildReportExportJob.php
│   │       │   └── SendReportExportReadyEmailJob.php
│   │       ├── Queries
│   │       │   ├── CollectionReportQuery.php
│   │       │   ├── DashboardMetricsQuery.php
│   │       │   ├── DigitalAccessReportQuery.php
│   │       │   ├── FineReportQuery.php
│   │       │   ├── MemberReportQuery.php
│   │       │   └── PopularCollectionQuery.php
│   │       ├── Services
│   │       │   ├── CirculationReportService.php
│   │       │   ├── CollectionReportService.php
│   │       │   ├── DigitalAccessReportService.php
│   │       │   ├── FineReportService.php
│   │       │   ├── MemberReportService.php
│   │       │   ├── PopularCollectionReportService.php
│   │       │   ├── ReportExportService.php
│   │       │   └── ReportingDashboardService.php
│   │       └── Support
│   │           └── Export
│   │               ├── CirculationReportExporter.php
│   │               ├── CollectionReportExporter.php
│   │               ├── DigitalAccessReportExporter.php
│   │               ├── FineReportExporter.php
│   │               ├── MemberReportExporter.php
│   │               └── PopularCollectionReportExporter.php
│   ├── Providers
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   ├── HorizonServiceProvider.php
│   │   └── RouteServiceProvider.php
│   └── Support
│       ├── ErrorCode
│       │   ├── DomainExceptionFactory.php
│       │   ├── ErrorCatalog.php
│       │   ├── ErrorCode.php
│       │   └── ErrorMessageResolver.php
│       ├── Helpers
│       │   ├── DateHelper.php
│       │   ├── FileHelper.php
│       │   ├── NumberHelper.php
│       │   └── StringHelper.php
│       └── Security
│           ├── RateLimitResolver.php
│           ├── SecurityHeaderResolver.php
│           └── SensitiveFieldMasker.php
├── bootstrap
│   ├── app.php
│   └── cache
│       ├── .gitignore
│       └── packages.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── hashing.php
│   ├── horizon.php
│   ├── logging.php
│   ├── mail.php
│   ├── permission.php
│   ├── queue.php
│   ├── scout.php
│   ├── services.php
│   ├── session.php
│   ├── view.php
│   └── perpusqu
│       ├── app.php
│       ├── audit.php
│       ├── features.php
│       ├── import_export.php
│       ├── ocr.php
│       ├── reporting.php
│       ├── search.php
│       ├── security.php
│       └── storage.php
├── database
│   ├── factories
│   │   ├── BibliographicRecordFactory.php
│   │   ├── DigitalAssetFactory.php
│   │   ├── LoanFactory.php
│   │   ├── MemberFactory.php
│   │   ├── PhysicalItemFactory.php
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 0001_00_00_000000_create_users_table.php
│   │   ├── 0001_00_00_000001_create_cache_table.php
│   │   ├── 0001_00_00_000002_create_jobs_table.php
│   │   ├── 2026_01_01_000001_create_permission_tables.php
│   │   ├── 2026_01_01_000002_create_institution_profiles_table.php
│   │   ├── 2026_01_01_000003_create_system_settings_table.php
│   │   ├── 2026_01_01_000004_create_authors_table.php
│   │   ├── 2026_01_01_000005_create_publishers_table.php
│   │   ├── 2026_01_01_000006_create_languages_table.php
│   │   ├── 2026_01_01_000007_create_classifications_table.php
│   │   ├── 2026_01_01_000008_create_subjects_table.php
│   │   ├── 2026_01_01_000009_create_collection_types_table.php
│   │   ├── 2026_01_01_000010_create_rack_locations_table.php
│   │   ├── 2026_01_01_000011_create_item_conditions_table.php
│   │   ├── 2026_01_01_000012_create_faculties_table.php
│   │   ├── 2026_01_01_000013_create_study_programs_table.php
│   │   ├── 2026_01_01_000014_create_bibliographic_records_table.php
│   │   ├── 2026_01_01_000015_create_bibliographic_record_authors_table.php
│   │   ├── 2026_01_01_000016_create_bibliographic_record_subjects_table.php
│   │   ├── 2026_01_01_000017_create_physical_items_table.php
│   │   ├── 2026_01_01_000018_create_physical_item_status_histories_table.php
│   │   ├── 2026_01_01_000019_create_members_table.php
│   │   ├── 2026_01_01_000020_create_loans_table.php
│   │   ├── 2026_01_01_000021_create_loan_renewals_table.php
│   │   ├── 2026_01_01_000022_create_return_transactions_table.php
│   │   ├── 2026_01_01_000023_create_fines_table.php
│   │   ├── 2026_01_01_000024_create_digital_assets_table.php
│   │   ├── 2026_01_01_000025_create_digital_asset_access_rules_table.php
│   │   ├── 2026_01_01_000026_create_ocr_texts_table.php
│   │   ├── 2026_01_01_000027_create_activity_logs_table.php
│   │   ├── 2026_01_01_000028_create_report_export_histories_table.php
│   │   └── 2026_01_01_000029_create_queue_monitor_snapshots_table.php
│   └── seeders
│       ├── AuthorSeeder.php
│       ├── ClassificationSeeder.php
│       ├── CollectionTypeSeeder.php
│       ├── DatabaseSeeder.php
│       ├── FacultySeeder.php
│       ├── InstitutionProfileSeeder.php
│       ├── ItemConditionSeeder.php
│       ├── LanguageSeeder.php
│       ├── PermissionSeeder.php
│       ├── PublisherSeeder.php
│       ├── RackLocationSeeder.php
│       ├── RolePermissionSeeder.php
│       ├── RoleSeeder.php
│       ├── StudyProgramSeeder.php
│       ├── SubjectSeeder.php
│       ├── SuperAdminSeeder.php
│       └── SystemSettingSeeder.php
├── docs
│   ├── blueprints
│   │   ├── 01_EXECUTIVE_SUMMARY.md
│   │   ├── 02_STACK_TEKNOLOGI.md
│   │   ├── 03_ARSITEKTUR_MODULAR.md
│   │   ├── 04_PRD.md
│   │   ├── 05_SRS.md
│   │   ├── 06_USE_CASE.md
│   │   ├── 07_ROLE_PERMISSION_MATRIX.md
│   │   ├── 08_MENU_MAP.md
│   │   ├── 09_ROUTE_MAP.md
│   │   ├── 10_VIEW_MAP.md
│   │   ├── 11_CONTROLLER_MAP.md
│   │   ├── 12_SERVICE_LAYER.md
│   │   ├── 13_MODEL_MAP.md
│   │   ├── 14_SCHEMA.sql
│   │   ├── 15_SEED.sql
│   │   ├── 16_VALIDATION_RULES.md
│   │   ├── 17_WORKFLOW_STATE_MACHINE.md
│   │   ├── 18_UI_UX_STANDARD.md
│   │   ├── 19_OPAC_UX_FLOW.md
│   │   ├── 20_API_CONTRACT.md
│   │   ├── 21_SEARCH_INDEXING_SPEC.md
│   │   ├── 22_STORAGE_FILE_POLICY.md
│   │   ├── 23_OCR_AND_DIGITAL_PROCESSING.md
│   │   ├── 24_NOTIFICATION_RULES.md
│   │   ├── 25_REPORTING_SPEC.md
│   │   ├── 26_IMPORT_EXPORT_SPEC.md
│   │   ├── 27_INTEGRATION_SPEC.md
│   │   ├── 28_SECURITY_POLICY.md
│   │   ├── 29_AUDIT_LOG_SPEC.md
│   │   ├── 30_ERROR_CODE.md
│   │   ├── 31_TEST_PLAN.md
│   │   ├── 32_TEST_SCENARIO.md
│   │   ├── 33_DEPLOYMENT_GUIDE.md
│   │   ├── 34_ENV_CONFIGURATION.md
│   │   ├── 35_BACKUP_AND_RECOVERY.md
│   │   ├── 36_PERFORMANCE_GUIDE.md
│   │   ├── 37_CODING_STANDARD.md
│   │   └── 38_TREE.md
│   ├── operations
│   │   ├── backup
│   │   │   ├── backup_perpusqu_config.sh
│   │   │   ├── backup_perpusqu_database.sh
│   │   │   ├── backup_perpusqu_storage.sh
│   │   │   ├── cleanup_perpusqu_backup.sh
│   │   │   └── verify_perpusqu_backup.sh
│   │   ├── deploy
│   │   │   ├── deploy_staging.sh
│   │   │   ├── deploy_production.sh
│   │   │   └── rollback_release.sh
│   │   └── environment
│   │       ├── nginx
│   │       │   ├── perpusqu.conf
│   │       │   └── staging-perpusqu.conf
│   │       ├── supervisor
│   │       │   ├── perpusqu-horizon.conf
│   │       │   ├── perpusqu-worker-default.conf
│   │       │   └── perpusqu-worker-heavy.conf
│   │       └── systemd
│   │           └── perpusqu-scheduler.service
│   └── testing
│       ├── datasets
│       │   ├── members_import_invalid.csv
│       │   ├── members_import_valid.csv
│       │   ├── members_import_valid.xlsx
│       │   ├── sample_public_cover.jpg
│       │   ├── sample_scanned_pdf.pdf
│       │   └── sample_text_pdf.pdf
│       └── evidence
│           └── .gitkeep
├── public
│   ├── build
│   │   └── .gitignore
│   ├── favicon.ico
│   ├── index.php
│   ├── robots.txt
│   └── assets
│       ├── images
│       │   ├── default-cover.png
│       │   ├── empty-state.png
│       │   └── logo-placeholder.png
│       └── pdfjs
│           ├── viewer.html
│           └── pdf.worker.min.js
├── resources
│   ├── css
│   │   ├── admin.css
│   │   ├── app.css
│   │   └── opac.css
│   ├── js
│   │   ├── admin.js
│   │   ├── app.js
│   │   ├── bootstrap.js
│   │   ├── modules
│   │   │   ├── audit.js
│   │   │   ├── catalog.js
│   │   │   ├── circulation.js
│   │   │   ├── digital-assets.js
│   │   │   ├── import-export.js
│   │   │   ├── reports.js
│   │   │   └── search.js
│   │   └── opac.js
│   └── views
│       ├── components
│       │   ├── admin
│       │   │   ├── _breadcrumb.blade.php
│       │   │   ├── _datatable_pagination.blade.php
│       │   │   ├── _empty_state.blade.php
│       │   │   ├── _filter_bar.blade.php
│       │   │   ├── _flash_message.blade.php
│       │   │   ├── _page_header.blade.php
│       │   │   ├── _status_badge.blade.php
│       │   │   └── _summary_cards.blade.php
│       │   └── opac
│       │       ├── _asset_preview_button.blade.php
│       │       ├── _record_card.blade.php
│       │       ├── _search_empty_state.blade.php
│       │       ├── _search_filter_bar.blade.php
│       │       └── _search_result_meta.blade.php
│       ├── errors
│       │   ├── 403.blade.php
│       │   ├── 404.blade.php
│       │   ├── 419.blade.php
│       │   ├── 429.blade.php
│       │   └── 500.blade.php
│       ├── layouts
│       │   ├── admin.blade.php
│       │   ├── app.blade.php
│       │   ├── auth.blade.php
│       │   └── opac.blade.php
│       ├── modules
│       │   ├── audit
│       │   │   └── logs
│       │   │       ├── _filter.blade.php
│       │   │       ├── _table.blade.php
│       │   │       ├── index.blade.php
│       │   │       └── show.blade.php
│       │   ├── catalog
│       │   │   └── records
│       │   │       ├── _form.blade.php
│       │   │       ├── _table.blade.php
│       │   │       ├── create.blade.php
│       │   │       ├── edit.blade.php
│       │   │       ├── index.blade.php
│       │   │       └── show.blade.php
│       │   ├── collection
│       │   │   └── items
│       │   │       ├── _form.blade.php
│       │   │       ├── _status_modal.blade.php
│       │   │       ├── _table.blade.php
│       │   │       ├── create.blade.php
│       │   │       ├── edit.blade.php
│       │   │       ├── index.blade.php
│       │   │       └── show.blade.php
│       │   ├── core
│       │   │   ├── dashboard
│       │   │   │   └── index.blade.php
│       │   │   ├── institution_profile
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── edit.blade.php
│       │   │   └── system_settings
│       │   │       ├── _form.blade.php
│       │   │       └── edit.blade.php
│       │   ├── digital_repository
│       │   │   └── assets
│       │   │       ├── _access_rule_form.blade.php
│       │   │       ├── _form.blade.php
│       │   │       ├── _ocr_status_card.blade.php
│       │   │       ├── _table.blade.php
│       │   │       ├── create.blade.php
│       │   │       ├── edit.blade.php
│       │   │       ├── index.blade.php
│       │   │       ├── preview.blade.php
│       │   │       └── show.blade.php
│       │   ├── identity
│       │   │   ├── roles
│       │   │   │   ├── _form.blade.php
│       │   │   │   ├── _permission_matrix.blade.php
│       │   │   │   ├── create.blade.php
│       │   │   │   ├── edit.blade.php
│       │   │   │   └── index.blade.php
│       │   │   └── users
│       │   │       ├── _form.blade.php
│       │   │       ├── _reset_password_modal.blade.php
│       │   │       ├── _table.blade.php
│       │   │       ├── create.blade.php
│       │   │       ├── edit.blade.php
│       │   │       ├── index.blade.php
│       │   │       └── show.blade.php
│       │   ├── master_data
│       │   │   ├── authors
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── classifications
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── collection_types
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── faculties
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── languages
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── publishers
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── rack_locations
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   ├── study_programs
│       │   │   │   ├── _form.blade.php
│       │   │   │   └── index.blade.php
│       │   │   └── subjects
│       │   │       ├── _form.blade.php
│       │   │       └── index.blade.php
│       │   ├── member
│       │   │   ├── imports
│       │   │   │   ├── _result_summary.blade.php
│       │   │   │   └── index.blade.php
│       │   │   └── members
│       │   │       ├── _block_modal.blade.php
│       │   │       ├── _form.blade.php
│       │   │       ├── _table.blade.php
│       │   │       ├── create.blade.php
│       │   │       ├── edit.blade.php
│       │   │       ├── index.blade.php
│       │   │       └── show.blade.php
│       │   ├── profile
│       │   │   ├── _password_form.blade.php
│       │   │   ├── _profile_form.blade.php
│       │   │   └── edit.blade.php
│       │   └── reporting
│       │       ├── circulation
│       │       │   ├── _filter.blade.php
│       │       │   ├── _summary.blade.php
│       │       │   └── index.blade.php
│       │       ├── collections
│       │       │   ├── _filter.blade.php
│       │       │   ├── _summary.blade.php
│       │       │   └── index.blade.php
│       │       ├── dashboard.blade.php
│       │       ├── digital_access
│       │       │   ├── _filter.blade.php
│       │       │   ├── _summary.blade.php
│       │       │   └── index.blade.php
│       │       ├── fines
│       │       │   ├── _filter.blade.php
│       │       │   ├── _summary.blade.php
│       │       │   └── index.blade.php
│       │       ├── members
│       │       │   ├── _filter.blade.php
│       │       │   ├── _summary.blade.php
│       │       │   └── index.blade.php
│       │       └── popular_collections
│       │           ├── _filter.blade.php
│       │           ├── _summary.blade.php
│       │           └── index.blade.php
│       ├── opac
│       │   ├── about.blade.php
│       │   ├── help.blade.php
│       │   ├── home.blade.php
│       │   ├── records
│       │   │   └── show.blade.php
│       │   └── search
│       │       └── index.blade.php
│       └── auth
│           └── login.blade.php
├── routes
│   ├── api.php
│   ├── channels.php
│   ├── console.php
│   ├── web.php
│   ├── admin
│   │   ├── audit.php
│   │   ├── catalog.php
│   │   ├── collection.php
│   │   ├── core.php
│   │   ├── digital_repository.php
│   │   ├── identity.php
│   │   ├── master_data.php
│   │   ├── member.php
│   │   ├── profile.php
│   │   └── reporting.php
│   ├── api_internal
│   │   ├── lookup.php
│   │   ├── search.php
│   │   └── digital_assets.php
│   └── opac.php
├── storage
│   ├── app
│   │   ├── .gitignore
│   │   ├── private
│   │   │   ├── exports
│   │   │   ├── imports
│   │   │   └── temp
│   │   │       ├── export
│   │   │       └── ocr
│   │   └── public
│   │       └── .gitignore
│   ├── framework
│   │   ├── cache
│   │   ├── sessions
│   │   ├── testing
│   │   └── views
│   └── logs
│       └── laravel.log
├── tests
│   ├── CreatesApplication.php
│   ├── Feature
│   │   ├── Admin
│   │   │   ├── Audit
│   │   │   │   └── AuditLogFeatureTest.php
│   │   │   ├── Catalog
│   │   │   │   ├── BibliographicRecordFeatureTest.php
│   │   │   │   └── PublishBibliographicRecordFeatureTest.php
│   │   │   ├── Collection
│   │   │   │   └── PhysicalItemFeatureTest.php
│   │   │   ├── Core
│   │   │   │   ├── DashboardFeatureTest.php
│   │   │   │   └── InstitutionProfileFeatureTest.php
│   │   │   ├── DigitalRepository
│   │   │   │   ├── DigitalAssetFeatureTest.php
│   │   │   │   ├── DigitalAssetPreviewFeatureTest.php
│   │   │   │   └── OcrDispatchFeatureTest.php
│   │   │   ├── Identity
│   │   │   │   ├── LoginFeatureTest.php
│   │   │   │   ├── RolePermissionFeatureTest.php
│   │   │   │   └── UserManagementFeatureTest.php
│   │   │   ├── MasterData
│   │   │   │   ├── AuthorFeatureTest.php
│   │   │   │   ├── FacultyFeatureTest.php
│   │   │   │   └── SubjectFeatureTest.php
│   │   │   ├── Member
│   │   │   │   ├── MemberFeatureTest.php
│   │   │   │   └── MemberImportFeatureTest.php
│   │   │   ├── Profile
│   │   │   │   └── ProfileFeatureTest.php
│   │   │   └── Reporting
│   │   │       ├── CollectionReportFeatureTest.php
│   │   │       ├── DashboardReportFeatureTest.php
│   │   │       ├── ExportReportFeatureTest.php
│   │   │       └── MemberReportFeatureTest.php
│   │   ├── Circulation
│   │   │   ├── LoanFeatureTest.php
│   │   │   ├── ReturnFeatureTest.php
│   │   │   └── RenewLoanFeatureTest.php
│   │   ├── Opac
│   │   │   ├── OpacSearchFeatureTest.php
│   │   │   ├── PublicPreviewFeatureTest.php
│   │   │   └── RecordDetailFeatureTest.php
│   │   └── Security
│   │       ├── AccessDeniedFeatureTest.php
│   │       ├── CsrfProtectionFeatureTest.php
│   │       ├── PrivateAssetSecurityFeatureTest.php
│   │       └── PublicVisibilityFeatureTest.php
│   ├── Integration
│   │   ├── CirculationIntegrationTest.php
│   │   ├── DigitalAssetSearchIntegrationTest.php
│   │   ├── ImportExportIntegrationTest.php
│   │   ├── OcrIntegrationTest.php
│   │   ├── ReportingIntegrationTest.php
│   │   └── SearchReindexIntegrationTest.php
│   ├── TestCase.php
│   └── Unit
│       ├── Audit
│       │   ├── AuditValueSanitizerTest.php
│       │   └── QueueHealthResolverTest.php
│       ├── Catalog
│       │   ├── BibliographicRecordHydratorTest.php
│       │   └── BibliographicRecordStateGuardTest.php
│       ├── Circulation
│       │   ├── DueDateCalculatorTest.php
│       │   ├── FineAmountCalculatorTest.php
│       │   ├── LoanEligibilityServiceTest.php
│       │   └── PhysicalItemStateGuardTest.php
│       ├── DigitalRepository
│       │   ├── OcrProcessingServiceTest.php
│       │   ├── OcrTextNormalizerTest.php
│       │   ├── PublicAssetVisibilityResolverTest.php
│       │   └── TempFileManagerTest.php
│       ├── Identity
│       │   ├── AuthenticationServiceTest.php
│       │   └── PermissionMatrixResolverTest.php
│       ├── Member
│       │   ├── MemberBlockingServiceTest.php
│       │   ├── MemberEligibilityResolverTest.php
│       │   └── MemberImportRowValidatorTest.php
│       ├── Reporting
│       │   ├── DashboardMetricsQueryTest.php
│       │   ├── FineReportQueryTest.php
│       │   ├── MemberReportQueryTest.php
│       │   └── PopularCollectionQueryTest.php
│       └── Support
│           ├── ErrorCatalogTest.php
│           ├── ErrorMessageResolverTest.php
│           └── SensitiveFieldMaskerTest.php
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
├── README.md
└── vite.config.js
````

## 5. Aturan Wajib

1. Struktur file implementasi final wajib mengikuti tree ini.
2. File dan folder dapat ditambah hanya bila benar benar diperlukan dan tidak melanggar arsitektur modular.
3. Nama file, class, route, controller, service, request, job, policy, dan view wajib konsisten dengan dokumen blueprint sebelumnya.
4. Tidak boleh ada file liar tanpa posisi logis dalam struktur ini.
5. Folder dan file test wajib mengikuti domain dan prioritas test plan.

## 6. Catatan Implementasi

1. Tree ini adalah struktur resmi acuan coding.
2. Detail isi setiap file tetap wajib mengikuti 37_CODING_STANDARD.md.
3. Keterkaitan menu, route, controller, service, model, view, dan tabel akan dipetakan lebih lanjut pada 39_TRACEABILITY_MATRIX.md.

## 7. Kesimpulan

Dokumen TREE ini menetapkan struktur file dan folder resmi PERPUSQU agar implementasi coding tetap konsisten, modular, mudah ditelusuri, dan siap dipakai sebagai acuan AI Agent maupun tim developer.

END OF 38_TREE.md

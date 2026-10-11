<?php

namespace App\Filament\Resources\VisaTypeRequirementResource\Pages;

use App\Filament\Resources\VisaTypeRequirementResource;
use App\Models\Country;
use App\Models\CountryVisaType;
use App\Models\DocumentCategory;
use App\Models\VisaTypeRequirement;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class ListVisaTypeRequirements extends Page
{
    protected static string $resource = VisaTypeRequirementResource::class;

    protected static string $view = 'filament.pages.manage-visa-type-requirements';

    #[Url]
    public ?int $country_id = null;

    #[Url]
    public ?int $visa_type_id = null;

    public string $countrySearch = '';

    public string $visaTypeSearch = '';

    public string $categorySearch = '';

    // Add Country Modal
    public bool $showCountryModal = false;

    public string $newCountryName = '';

    public string $newCountryCode = '';

    public string $newCountryFlag = '';

    // Add Visa Type Modal
    public bool $showVisaTypeModal = false;

    public string $newVisaTypePreset = 'Tourist';

    public string $customVisaTypeName = '';

    // New Category Modal
    public bool $showNewCategoryModal = false;

    public string $newCategoryName = '';

    public function getTitle(): string
    {
        if ($this->selectedVisaType) {
            $flag = $this->selectedCountry?->flag ? $this->selectedCountry->flag.' ' : '';

            return $flag.$this->selectedCountry?->name.' — '.$this->selectedVisaType->name.' Visa';
        }

        if ($this->selectedCountry) {
            $flag = $this->selectedCountry->flag ? $this->selectedCountry->flag.' ' : '';

            return $flag.$this->selectedCountry->name.' — Visa Categories';
        }

        return 'Visa Document Requirements';
    }

    public function getSubheading(): ?string
    {
        if ($this->selectedVisaType) {
            return "Configure required and optional documents for {$this->selectedVisaType->display_name}.";
        }

        if ($this->selectedCountry) {
            return "Manage visa types and upload requirements for {$this->selectedCountry->name}.";
        }

        return 'Select a destination country to configure its visa categories and required documents.';
    }

    protected function getHeaderActions(): array
    {
        if (! $this->country_id) {
            return [
                Action::make('addCountry')
                    ->label('Add Country')
                    ->icon('heroicon-m-plus')
                    ->color('primary')
                    ->modalHeading('Add New Country')
                    ->modalDescription('Register a destination country to configure its visa categories.')
                    ->modalSubmitActionLabel('Create Country')
                    ->form([
                        TextInput::make('name')
                            ->label('Country Name')
                            ->placeholder('e.g. Switzerland, Portugal')
                            ->required(),
                        TextInput::make('code')
                            ->label('ISO Code (optional)')
                            ->placeholder('e.g. CH, PT')
                            ->maxLength(10),
                        TextInput::make('flag')
                            ->label('Flag Emoji (optional)')
                            ->placeholder('e.g. 🇨🇭, 🇵🇹')
                            ->helperText('Emoji flag will be automatically selected if left empty.'),
                    ])
                    ->action(function (array $data): void {
                        $flag = $data['flag'] ?: self::detectFlag($data['name']);
                        $code = strtoupper(trim($data['code'] ?: substr($data['name'], 0, 2)));

                        $country = Country::create([
                            'name' => trim($data['name']),
                            'slug' => Str::slug($data['name']),
                            'code' => $code,
                            'flag' => $flag,
                            'is_active' => true,
                        ]);

                        $this->country_id = $country->id;
                        $this->visa_type_id = null;

                        Notification::make()
                            ->title('Country created successfully')
                            ->body("{$country->displayName} has been added. Now add its visa categories.")
                            ->success()
                            ->send();
                    }),
            ];
        }

        if ($this->country_id && ! $this->visa_type_id) {
            return [
                Action::make('backToCountries')
                    ->label('Back to Countries')
                    ->icon('heroicon-m-arrow-left')
                    ->color('gray')
                    ->action(fn () => $this->backToCountries()),

                Action::make('addVisaType')
                    ->label('Add Visa Type')
                    ->icon('heroicon-m-plus')
                    ->color('primary')
                    ->modalHeading("Add Visa Type for {$this->selectedCountry?->name}")
                    ->modalSubmitActionLabel('Create Visa Type')
                    ->form([
                        Select::make('preset')
                            ->label('Visa Type')
                            ->options([
                                'Tourist' => 'Tourist Visa',
                                'Business' => 'Business Visa',
                                'Student' => 'Student Visa',
                                'Work' => 'Work Visa',
                                'Medical' => 'Medical Visa',
                                'Family' => 'Family / Spouse Visa',
                                'Transit' => 'Transit Visa',
                                'Umrah' => 'Umrah Visa',
                                'PR' => 'Permanent Residency (PR) / Investor',
                                'Double Entry' => 'Double Entry Visa',
                                'Appeal' => 'Visa Appeal / Refusal Review',
                                'custom' => '+ Custom Visa Name...',
                            ])
                            ->default('Tourist')
                            ->required()
                            ->live(),
                        TextInput::make('custom_name')
                            ->label('Custom Visa Name')
                            ->visible(fn (Get $get): bool => $get('preset') === 'custom')
                            ->required(fn (Get $get): bool => $get('preset') === 'custom'),
                    ])
                    ->action(function (array $data): void {
                        $visaName = $data['preset'] === 'custom' ? trim($data['custom_name']) : trim($data['preset']);

                        $visaType = CountryVisaType::create([
                            'country_id' => $this->selectedCountry->id,
                            'name' => $visaName,
                            'display_name' => Str::endsWith($visaName, 'Visa') ? $visaName : $visaName.' Visa',
                            'slug' => Str::slug($visaName),
                            'is_active' => true,
                        ]);

                        $this->visa_type_id = $visaType->id;

                        Notification::make()
                            ->title('Visa type created')
                            ->body("{$visaType->display_name} has been added.")
                            ->success()
                            ->send();
                    }),
            ];
        }

        return [
            Action::make('backToVisaTypes')
                ->label('Back to Visa Categories')
                ->icon('heroicon-m-arrow-left')
                ->color('gray')
                ->action(fn () => $this->backToVisaTypes()),

            Action::make('applyCoreRequirements')
                ->label('Add Core 4')
                ->icon('heroicon-m-bolt')
                ->color('primary')
                ->action(fn () => $this->applyCoreRequirements()),

            Action::make('addCategory')
                ->label('New Category')
                ->icon('heroicon-m-plus')
                ->color('gray')
                ->modalHeading('Create Document Category')
                ->modalSubmitActionLabel('Create & Add')
                ->form([
                    TextInput::make('name')
                        ->label('Category Name')
                        ->placeholder('e.g. Vaccination Certificate')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $cat = DocumentCategory::create([
                        'name' => trim($data['name']),
                        'slug' => Str::slug($data['name']),
                        'is_predefined' => false,
                        'is_active' => true,
                    ]);

                    if ($this->selectedVisaType && $this->selectedCountry) {
                        VisaTypeRequirement::create([
                            'country_visa_type_id' => $this->selectedVisaType->id,
                            'country' => $this->selectedCountry->code === 'GLOBAL' ? null : $this->selectedCountry->name,
                            'visa_type' => $this->selectedVisaType->name,
                            'document_category_id' => $cat->id,
                            'is_mandatory' => true,
                        ]);
                    }

                    Notification::make()
                        ->title('Category created')
                        ->body("Category '{$cat->name}' has been created and marked as required.")
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [
            VisaTypeRequirementResource::getUrl() => 'Visa Requirements',
        ];

        if ($this->selectedCountry) {
            $flag = $this->selectedCountry->flag ? $this->selectedCountry->flag.' ' : '';
            $breadcrumbs[VisaTypeRequirementResource::getUrl('index', ['country_id' => $this->selectedCountry->id])] = $flag.$this->selectedCountry->name;
        }

        if ($this->selectedVisaType) {
            $breadcrumbs[] = $this->selectedVisaType->name.' Visa';
        }

        return $breadcrumbs;
    }

    public function getSelectedCountryProperty(): ?Country
    {
        if (! $this->country_id) {
            return null;
        }

        return Country::with(['visaTypes.requirements.documentCategory'])->find($this->country_id);
    }

    public function getSelectedVisaTypeProperty(): ?CountryVisaType
    {
        if (! $this->visa_type_id) {
            return null;
        }

        return CountryVisaType::with(['country', 'requirements.documentCategory'])->find($this->visa_type_id);
    }

    public function getCountriesProperty(): Collection
    {
        return Country::withCount(['visaTypes'])
            ->with(['requirements'])
            ->when($this->countrySearch, function ($q) {
                $term = strtolower(trim($this->countrySearch));
                $q->where(function ($sub) use ($term) {
                    $sub->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(code) LIKE ?', ["%{$term}%"]);
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getCountryVisaTypesProperty(): Collection
    {
        if (! $this->selectedCountry) {
            return collect();
        }

        return $this->selectedCountry->visaTypes()
            ->with(['requirements.documentCategory'])
            ->when($this->visaTypeSearch, function ($q) {
                $term = strtolower(trim($this->visaTypeSearch));
                $q->where(function ($sub) use ($term) {
                    $sub->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(display_name) LIKE ?', ["%{$term}%"]);
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    public function getAllCategoriesProperty(): Collection
    {
        return DocumentCategory::active()
            ->ordered()
            ->when($this->categorySearch, function ($q) {
                $term = strtolower(trim($this->categorySearch));
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]);
            })
            ->get();
    }

    // Navigation methods
    public function selectCountry(int $countryId): void
    {
        $this->country_id = $countryId;
        $this->visa_type_id = null;
        $this->visaTypeSearch = '';
    }

    public function backToCountries(): void
    {
        $this->country_id = null;
        $this->visa_type_id = null;
    }

    public function selectVisaType(int $visaTypeId): void
    {
        $this->visa_type_id = $visaTypeId;
        $this->categorySearch = '';
    }

    public function backToVisaTypes(): void
    {
        $this->visa_type_id = null;
    }

    // Add Country
    public function openCountryModal(): void
    {
        $this->newCountryName = '';
        $this->newCountryCode = '';
        $this->newCountryFlag = '';
        $this->showCountryModal = true;
    }

    public function closeCountryModal(): void
    {
        $this->showCountryModal = false;
    }

    public function saveCountry(): void
    {
        $this->validate([
            'newCountryName' => 'required|string|max:100|unique:countries,name',
            'newCountryCode' => 'nullable|string|max:10',
            'newCountryFlag' => 'nullable|string|max:20',
        ]);

        $flag = $this->newCountryFlag ?: self::detectFlag($this->newCountryName);
        $code = strtoupper(trim($this->newCountryCode ?: substr($this->newCountryName, 0, 2)));

        $country = Country::create([
            'name' => trim($this->newCountryName),
            'slug' => Str::slug($this->newCountryName),
            'code' => $code,
            'flag' => $flag,
            'is_active' => true,
        ]);

        $this->showCountryModal = false;
        $this->country_id = $country->id;
        $this->visa_type_id = null;

        Notification::make()
            ->title('Country created successfully')
            ->body("{$country->displayName} has been added. You can now add its visa categories.")
            ->success()
            ->send();
    }

    public function deleteCountry(int $countryId): void
    {
        $country = Country::find($countryId);
        if ($country) {
            $name = $country->name;
            $country->delete();

            if ($this->country_id === $countryId) {
                $this->country_id = null;
                $this->visa_type_id = null;
            }

            Notification::make()
                ->title('Country removed')
                ->body("{$name} and its visa categories have been removed.")
                ->danger()
                ->send();
        }
    }

    // Add Visa Type
    public function openVisaTypeModal(): void
    {
        $this->newVisaTypePreset = 'Tourist';
        $this->customVisaTypeName = '';
        $this->showVisaTypeModal = true;
    }

    public function closeVisaTypeModal(): void
    {
        $this->showVisaTypeModal = false;
    }

    public function saveVisaType(): void
    {
        if (! $this->selectedCountry) {
            return;
        }

        $visaName = $this->newVisaTypePreset === 'custom'
            ? trim($this->customVisaTypeName)
            : trim($this->newVisaTypePreset);

        if (empty($visaName)) {
            $this->addError('customVisaTypeName', 'Please provide a visa type name.');

            return;
        }

        // Check if already exists under this country
        $existing = CountryVisaType::where('country_id', $this->selectedCountry->id)
            ->whereRaw('LOWER(name) = ?', [strtolower($visaName)])
            ->first();

        if ($existing) {
            $this->addError('customVisaTypeName', 'This visa type already exists for this country.');

            return;
        }

        $visaType = CountryVisaType::create([
            'country_id' => $this->selectedCountry->id,
            'name' => $visaName,
            'display_name' => Str::endsWith($visaName, 'Visa') ? $visaName : $visaName.' Visa',
            'slug' => Str::slug($visaName),
            'is_active' => true,
        ]);

        $this->showVisaTypeModal = false;
        $this->visa_type_id = $visaType->id;

        Notification::make()
            ->title('Visa type created')
            ->body("{$visaType->display_name} has been added for {$this->selectedCountry->displayName}. Now select required documents.")
            ->success()
            ->send();
    }

    public function deleteVisaType(int $visaTypeId): void
    {
        $vt = CountryVisaType::find($visaTypeId);
        if ($vt) {
            $name = $vt->display_name;
            $vt->delete();

            if ($this->visa_type_id === $visaTypeId) {
                $this->visa_type_id = null;
            }

            Notification::make()
                ->title('Visa type removed')
                ->body("{$name} and its requirements have been deleted.")
                ->danger()
                ->send();
        }
    }

    // Toggle Requirements (Level 3)
    public function toggleRequirement(int $categoryId): void
    {
        if (! $this->selectedVisaType || ! $this->selectedCountry) {
            return;
        }

        $countryName = $this->selectedCountry->code === 'GLOBAL' ? null : $this->selectedCountry->name;
        $visaTypeName = $this->selectedVisaType->name;

        $existing = VisaTypeRequirement::where('country_visa_type_id', $this->selectedVisaType->id)
            ->where('document_category_id', $categoryId)
            ->first();

        if ($existing) {
            $catName = $existing->documentCategory?->name;
            $existing->delete();

            Notification::make()
                ->title('Requirement removed')
                ->body("{$catName} unlinked from {$this->selectedVisaType->display_name}.")
                ->warning()
                ->send();
        } else {
            $category = DocumentCategory::find($categoryId);
            VisaTypeRequirement::create([
                'country_visa_type_id' => $this->selectedVisaType->id,
                'country' => $countryName,
                'visa_type' => $visaTypeName,
                'document_category_id' => $categoryId,
                'is_mandatory' => true,
            ]);

            Notification::make()
                ->title('Requirement added')
                ->body("{$category?->name} marked as required for {$this->selectedVisaType->display_name}.")
                ->success()
                ->send();
        }

        $this->dispatch('$refresh');
    }

    public function toggleMandatory(int $requirementId): void
    {
        $requirement = VisaTypeRequirement::find($requirementId);
        if (! $requirement) {
            return;
        }

        $requirement->update([
            'is_mandatory' => ! $requirement->is_mandatory,
        ]);

        $statusText = $requirement->is_mandatory ? 'Mandatory' : 'Optional';
        Notification::make()
            ->title("Status updated to {$statusText}")
            ->body("{$requirement->documentCategory?->name} is now {$statusText}.")
            ->info()
            ->send();

        $this->dispatch('$refresh');
    }

    public function applyCoreRequirements(): void
    {
        if (! $this->selectedVisaType || ! $this->selectedCountry) {
            return;
        }

        $countryName = $this->selectedCountry->code === 'GLOBAL' ? null : $this->selectedCountry->name;
        $visaTypeName = $this->selectedVisaType->name;

        $coreCategories = DocumentCategory::whereIn('slug', [
            'passport', 'photograph', 'bank-statement', 'visa-application-form',
        ])->get();

        $addedCount = 0;
        foreach ($coreCategories as $cat) {
            $req = VisaTypeRequirement::firstOrCreate([
                'country_visa_type_id' => $this->selectedVisaType->id,
                'document_category_id' => $cat->id,
            ], [
                'country' => $countryName,
                'visa_type' => $visaTypeName,
                'is_mandatory' => true,
            ]);

            if ($req->wasRecentlyCreated) {
                $addedCount++;
            }
        }

        Notification::make()
            ->title('Core requirements configured')
            ->body("Passport, Photograph, Bank Statement, and Visa Form added ({$addedCount} new).")
            ->success()
            ->send();
    }

    public function clearAllRequirements(): void
    {
        if (! $this->selectedVisaType) {
            return;
        }

        $count = $this->selectedVisaType->requirements()->count();
        $this->selectedVisaType->requirements()->delete();

        Notification::make()
            ->title('All requirements cleared')
            ->body("Cleared {$count} required documents.")
            ->danger()
            ->send();
    }

    // Add New Document Category on the fly
    public function openNewCategoryModal(): void
    {
        $this->newCategoryName = '';
        $this->showNewCategoryModal = true;
    }

    public function closeNewCategoryModal(): void
    {
        $this->showNewCategoryModal = false;
    }

    public function saveNewCategory(): void
    {
        $this->validate([
            'newCategoryName' => 'required|string|max:100|unique:document_categories,name',
        ]);

        $cat = DocumentCategory::create([
            'name' => trim($this->newCategoryName),
            'slug' => Str::slug($this->newCategoryName),
            'is_predefined' => false,
            'is_active' => true,
        ]);

        // If currently in Level 3, auto-attach to this visa type
        if ($this->selectedVisaType && $this->selectedCountry) {
            VisaTypeRequirement::create([
                'country_visa_type_id' => $this->selectedVisaType->id,
                'country' => $this->selectedCountry->code === 'GLOBAL' ? null : $this->selectedCountry->name,
                'visa_type' => $this->selectedVisaType->name,
                'document_category_id' => $cat->id,
                'is_mandatory' => true,
            ]);
        }

        $this->showNewCategoryModal = false;

        Notification::make()
            ->title('Document Category created')
            ->body("Category '{$cat->name}' has been created and marked as required.")
            ->success()
            ->send();
    }

    public static function detectFlag(string $countryName): string
    {
        $flags = [
            'India' => '🇮🇳', 'Spain' => '🇪🇸', 'USA' => '🇺🇸', 'United States' => '🇺🇸',
            'UK' => '🇬🇧', 'United Kingdom' => '🇬🇧', 'Canada' => '🇨🇦', 'Australia' => '🇦🇺',
            'Germany' => '🇩🇪', 'France' => '🇫🇷', 'Italy' => '🇮🇹', 'Japan' => '🇯🇵',
            'Saudi Arabia' => '🇸🇦', 'UAE' => '🇦🇪', 'United Arab Emirates' => '🇦🇪',
            'Singapore' => '🇸🇬', 'Malaysia' => '🇲🇾', 'Turkey' => '🇹🇷', 'Qatar' => '🇶🇦',
            'Portugal' => '🇵🇹', 'Sweden' => '🇸🇪', 'South Korea' => '🇰🇷', 'New Zealand' => '🇳🇿',
            'Brazil' => '🇧🇷', 'China' => '🇨🇳', 'Bangladesh' => '🇧🇩', 'Pakistan' => '🇵🇰',
            'Thailand' => '🇹🇭', 'Vietnam' => '🇻🇳', 'Indonesia' => '🇮🇩', 'Egypt' => '🇪🇬',
            'All Countries (Global Default)' => '🌐', 'Global' => '🌐',
        ];

        return $flags[$countryName] ?? '🌍';
    }
}

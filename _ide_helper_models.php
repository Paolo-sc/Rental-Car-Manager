<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $rental_contract_id
 * @property string $charge_type
 * @property string $description
 * @property numeric $amount
 * @property numeric|null $quantity
 * @property numeric|null $unit_price
 * @property int $added_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $addedByUser
 * @property-read \App\Models\RentalContract $rentalContract
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereChargeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereRentalContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdditionalCharge whereUpdatedAt($value)
 */
	class AdditionalCharge extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $customer_type
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $company_name
 * @property string $email
 * @property string $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $tax_code
 * @property string|null $vat_number
 * @property string|null $id_document_number
 * @property string|null $notes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonDocument> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RentalContract> $rentalContracts
 * @property-read int|null $rental_contracts_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCustomerType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIdDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereVatNumber($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string $driving_license_number
 * @property string|null $driving_license_issue_place
 * @property \Illuminate\Support\Carbon|null $driving_license_issue_date
 * @property \Illuminate\Support\Carbon|null $driving_license_expires_at
 * @property string|null $tax_code
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $birth_place
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $country
 * @property string|null $notes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RentalContractDriver> $additionalRentalContracts
 * @property-read int|null $additional_rental_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RentalContract> $mainRentalContracts
 * @property-read int|null $main_rental_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonDocument> $personDocuments
 * @property-read int|null $person_documents_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereBirthPlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereDrivingLicenseExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereDrivingLicenseIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereDrivingLicenseIssuePlace($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereDrivingLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereTaxCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Driver whereUpdatedAt($value)
 */
	class Driver extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property bool $used
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation expired()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation valid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invitation whereUsed($value)
 */
	class Invitation extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $customer_id
 * @property int|null $driver_id
 * @property string $document_type
 * @property string|null $id_document_number
 * @property string $drive_file_id
 * @property string|null $drive_file_url
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property int|null $uploaded_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Driver|null $driver
 * @property-read \App\Models\User|null $uploadedByUser
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereDriveFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereDriveFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereIdDocumentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonDocument whereUploadedBy($value)
 */
	class PersonDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $contract_number
 * @property string|null $booking_code
 * @property int $customer_id
 * @property int $main_driver_id
 * @property int $vehicle_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property mixed|null $pickup_time
 * @property mixed|null $return_time
 * @property string|null $pickup_location
 * @property string|null $return_location
 * @property numeric $daily_rate
 * @property int $total_days
 * @property numeric $subtotal
 * @property numeric|null $discount_amount
 * @property numeric|null $tax_rate
 * @property numeric $tax_amount
 * @property numeric $total_amount
 * @property numeric|null $deposit_amount
 * @property string|null $deposit_payment_method
 * @property numeric|null $total_paid
 * @property string|null $status
 * @property bool|null $payment_received
 * @property \Illuminate\Support\Carbon|null $payment_date
 * @property string|null $payment_method
 * @property string|null $payment_notes
 * @property string $km_included_type
 * @property int|null $km_included_value
 * @property numeric|null $franchise_theft_fire
 * @property numeric|null $deductible_damage
 * @property numeric|null $deductible_rca
 * @property int|null $max_passengers
 * @property string|null $special_conditions
 * @property bool|null $customer_signature_required
 * @property bool|null $customer_signature_obtained
 * @property \Illuminate\Support\Carbon|null $signature_date
 * @property string|null $notes
 * @property string|null $contract_pdf_drive_file_id
 * @property string|null $contract_pdf_drive_file_url
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdditionalCharge> $additionalCharges
 * @property-read int|null $additional_charges_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RentalContractDriver> $additionalDrivers
 * @property-read int|null $additional_drivers_count
 * @property-read \App\Models\User $createdByUser
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Driver $mainDriver
 * @property-read \App\Models\Vehicle $vehicle
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleInspection> $vehicleInspections
 * @property-read int|null $vehicle_inspections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereBookingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereContractNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereContractPdfDriveFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereContractPdfDriveFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereCustomerSignatureObtained($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereCustomerSignatureRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereDailyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereDeductibleDamage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereDeductibleRca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereDepositAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereDepositPaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereFranchiseTheftFire($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereKmIncludedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereKmIncludedValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereMainDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereMaxPassengers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract wherePaymentNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract wherePaymentReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract wherePickupLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract wherePickupTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereReturnLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereReturnTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereSignatureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereSpecialConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereTotalDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereTotalPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContract whereVehicleId($value)
 */
	class RentalContract extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $rental_contract_id
 * @property int $driver_id
 * @property string|null $notes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdByUser
 * @property-read \App\Models\Driver $driver
 * @property-read \App\Models\RentalContract $rentalContract
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver whereRentalContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RentalContractDriver whereUpdatedAt($value)
 */
	class RentalContractDriver extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property string|null $status
 * @property string|null $invitation_token
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property int|null $created_by
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $google_drive_token
 * @property string|null $google_drive_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdditionalCharge> $additionalCharges
 * @property-read int|null $additional_charges_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $createdUsers
 * @property-read int|null $created_users_count
 * @property-read mixed $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PersonDocument> $personDocuments
 * @property-read int|null $person_documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RentalContract> $rentalContracts
 * @property-read int|null $rental_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleDocument> $vehicleDocuments
 * @property-read int|null $vehicle_documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleInspection> $vehicleInspections
 * @property-read int|null $vehicle_inspections_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleDriveName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleDriveToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereInvitationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $license_plate
 * @property string $brand
 * @property string $model
 * @property int $year
 * @property string|null $color
 * @property string $fuel_type
 * @property string $transmission
 * @property int $seats
 * @property string|null $vin
 * @property string|null $engine_size
 * @property int|null $mileage
 * @property string|null $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RentalContract> $rentalContracts
 * @property-read int|null $rental_contracts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleDocument> $vehicleDocuments
 * @property-read int|null $vehicle_documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VehicleInspection> $vehicleInspections
 * @property-read int|null $vehicle_inspections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereEngineSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereFuelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereMileage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereTransmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereVin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vehicle whereYear($value)
 */
	class Vehicle extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $vehicle_id
 * @property string $document_type
 * @property string $document_name
 * @property string $drive_file_id
 * @property string|null $drive_file_url
 * @property string|null $notes
 * @property int|null $uploaded_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $uploadedByUser
 * @property-read \App\Models\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereDocumentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereDocumentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereDriveFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereDriveFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereUploadedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleDocument whereVehicleId($value)
 */
	class VehicleDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $rental_contract_id
 * @property string $inspection_type
 * @property int|null $vehicle_mileage
 * @property string|null $fuel_level
 * @property string|null $exterior_condition
 * @property string|null $interior_condition
 * @property array<array-key, mixed>|null $damage_map_data
 * @property string|null $notes
 * @property int $inspected_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $inspectedByUser
 * @property-read \App\Models\RentalContract $rentalContract
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereDamageMapData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereExteriorCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereFuelLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereInspectedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereInspectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereInteriorCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereRentalContractId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleInspection whereVehicleMileage($value)
 */
	class VehicleInspection extends \Eloquent {}
}

